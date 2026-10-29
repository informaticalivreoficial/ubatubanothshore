<?php

namespace App\Support;

use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PropertyPricing
{
    public const COUPLE_GUESTS = 2;

    public static function baseNightlyRate(Property $property, int $guests = 1): float
    {
        if (max(1, $guests) >= self::COUPLE_GUESTS) {
            return (float) ($property->rental_value_couple ?: $property->rental_value ?: 0);
        }

        return (float) ($property->rental_value ?: 0);
    }

    public static function extraGuests(Property $property, int $guests): int
    {
        return max(0, $guests - self::COUPLE_GUESTS);
    }

    public static function seasonalRate(Property $property, Carbon $date, ?Collection $seasons = null): ?float
    {
        $seasons ??= $property->seasons()
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->get();

        $season = $seasons->first(fn ($season) => $date->betweenIncluded(
            Carbon::parse($season->start_date)->startOfDay(),
            Carbon::parse($season->end_date)->endOfDay()
        ));

        return $season ? (float) $season->price_per_day : null;
    }

    public static function nightlyRate(Property $property, $date, int $guests = 1, ?Collection $seasons = null): float
    {
        $date = $date instanceof Carbon ? $date->copy() : Carbon::parse($date);

        return self::seasonalRate($property, $date, $seasons)
            ?? self::baseNightlyRate($property, $guests);
    }

    public static function quote(Property $property, $checkIn, $checkOut, int $guests = 1): array
    {
        $checkIn = Carbon::parse($checkIn);
        $checkOut = Carbon::parse($checkOut);
        $guests = max(1, $guests);

        $nights = $checkIn->diffInDays($checkOut);

        if ($nights <= 0) {
            return [];
        }

        $seasons = $property->seasons()
            ->where('start_date', '<=', $checkOut->toDateString())
            ->where('end_date', '>=', $checkIn->toDateString())
            ->get();

        $dailyTotal = 0.0;
        $seasonApplied = false;
        $seasonNights = 0;
        $baseNights = 0;
        $current = $checkIn->copy();

        while ($current->lt($checkOut)) {
            $seasonalRate = self::seasonalRate($property, $current, $seasons);

            if ($seasonalRate !== null) {
                $seasonApplied = true;
                $seasonNights++;
                $dailyTotal += $seasonalRate;
            } else {
                $baseNights++;
                $dailyTotal += self::baseNightlyRate($property, $guests);
            }

            $current->addDay();
        }

        $extraGuests = self::extraGuests($property, $guests);
        $extraTotal = $extraGuests * (float) ($property->value_aditional ?? 0) * $nights;
        $cleaningFee = (float) ($property->cleaning_fee ?? 0);
        $iptuFee = (float) ($property->iptu_fee ?? 0);

        return [
            'nights' => $nights,
            'daily_total' => $dailyTotal,
            'extra_guests' => $extraGuests,
            'extra_total' => $extraTotal,
            'cleaning_fee' => $cleaningFee,
            'iptu_fee' => $iptuFee,
            'total_value' => $dailyTotal + $extraTotal + $cleaningFee + $iptuFee,
            'season_applied' => $seasonApplied,
            'season_nights' => $seasonNights,
            'base_nights' => $baseNights,
        ];
    }
}

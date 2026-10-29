<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertySeason extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'label',
        'start_date',
        'end_date',
        'price_per_day',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function nights(): int
    {
        return (int) Carbon::parse($this->start_date)
            ->diffInDays(Carbon::parse($this->end_date)) + 1;
    }

    public function isCoveredBy($checkIn, $checkOut): bool
    {
        $checkIn = Carbon::parse($checkIn)->startOfDay();
        $checkOut = Carbon::parse($checkOut)->startOfDay();

        return $checkIn->lte(Carbon::parse($this->start_date)->startOfDay())
            && $checkOut->gt(Carbon::parse($this->end_date)->startOfDay());
    }

    public function coverageMessage(): string
    {
        return sprintf(
            "A temporada '%s' exige que a reserva inclua todas as noites do período: %s até %s (%d noite(s)). Você pode estender a reserva antes ou depois da temporada.",
            $this->label,
            Carbon::parse($this->start_date)->format('d/m/Y'),
            Carbon::parse($this->end_date)->format('d/m/Y'),
            $this->nights()
        );
    }
}

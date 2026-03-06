<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'user_id',
        'review_token',
        'guest_name',
        'guest_email',
        'guest_phone',
        'check_in',
        'check_out',
        'nights',
        'daily_total',
        'notes',
        'guests',
        'cleaning_fee',
        'total_value',
        'origin',
        'status',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
    ];

    // protected static function booted()
    // {
    //     static::creating(function ($reservation) {

    //         $checkIn  = Carbon::parse($reservation->check_in);
    //         $checkOut = Carbon::parse($reservation->check_out);

    //         $nights = $checkIn->diffInDays($checkOut);

    //         $reservation->nights = $nights;

    //         // se vier daily_total vazio, calcula
    //         if (!$reservation->daily_total && $reservation->property) {

    //             $dailyTotal = $reservation->property
    //                 ->calculatePrice($reservation->check_in, $reservation->check_out);

    //             $reservation->daily_total = $dailyTotal;
    //         }

    //         $reservation->total_value =
    //             $reservation->daily_total + $reservation->cleaning_fee;
    //     });
    // }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function review()
    {
        return $this->hasOne(PropertyReview::class, 'reservation_id');
    }

    public static function calculateTotals(Property $property, $checkIn, $checkOut, $guests)
    {
        $checkIn = Carbon::parse($checkIn);
        $checkOut = Carbon::parse($checkOut);

        if ($checkOut->lte($checkIn)) {
            return null;
        }

        $nights = $checkIn->diffInDays($checkOut);

        $cleaningFee = $property->cleaning_fee ?? 0;

        $subtotal = $property->rental_value * $nights;

        $extraGuests = max(
            0,
            (int) $guests - (int) $property->aditional_person
        );

        $extraTotal =
            $extraGuests *
            $property->value_aditional *
            $nights;

        $total =
            $subtotal +
            $extraTotal +
            $cleaningFee;

        return [
            'nights' => $nights,
            'daily_total' => $subtotal,
            'cleaning_fee' => $cleaningFee,
            'total_value' => $total
        ];
    }
}

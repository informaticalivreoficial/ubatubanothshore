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
        'guest_name',
        'guest_email',
        'guest_phone',
        'check_in',
        'check_out',
        'nights',
        'daily_total',
        'cleaning_fee',
        'total_value',
        'origin',
        'status',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($reservation) {

            $checkIn  = Carbon::parse($reservation->check_in);
            $checkOut = Carbon::parse($reservation->check_out);

            $nights = $checkIn->diffInDays($checkOut);

            $reservation->nights = $nights;

            // se vier daily_total vazio, calcula
            if (!$reservation->daily_total && $reservation->property) {

                $dailyTotal = $reservation->property
                    ->calculatePrice($reservation->check_in, $reservation->check_out);

                $reservation->daily_total = $dailyTotal;
            }

            $reservation->total_value =
                $reservation->daily_total + $reservation->cleaning_fee;
        });
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

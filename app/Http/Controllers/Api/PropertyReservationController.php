<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyReservation;
use Illuminate\Http\Request;

class PropertyReservationController extends Controller
{
    public function calendar(Property $property)
    {
        $reservations = $property->reservations()
            ->where('status', '!=', 'cancelled')
            ->get(['check_in', 'check_out']);

        $blocked = $property->blockedDates()
            ->get(['date']);

        return response()->json([
            'reservations' => $reservations,
            'blocked_dates' => $blocked,
        ]);
    }

    public function checkAvailability(Request $request, Property $property)
    {
        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $available = $property->isAvailable(
            $request->check_in,
            $request->check_out
        );

        return response()->json([
            'available' => $available
        ]);
    }

    public function calculate(Request $request, Property $property)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $available = $property->isAvailable(
            $request->check_in,
            $request->check_out
        );

        if (!$available) {
            return response()->json([
                'available' => false
            ]);
        }

        $checkIn = \Carbon\Carbon::parse($request->check_in);
        $checkOut = \Carbon\Carbon::parse($request->check_out);

        $nights = $checkIn->diffInDays($checkOut);

        // valida mínimo de noites
        if ($nights < $property->min_nights) {
            return response()->json([
                'available' => false,
                'message' => 'Mínimo de ' . $property->min_nights . ' noites'
            ], 422);
        }

        $dailyTotal = $property->calculatePrice(
            $request->check_in,
            $request->check_out
        );

        $cleaningFee = $property->cleaning_fee ?? 0;

        return response()->json([
            'available' => true,
            'nights' => $nights,
            'daily_total' => $dailyTotal,
            'cleaning_fee' => $cleaningFee,
            'total_value' => $dailyTotal + $cleaningFee,
        ]);
    }

    public function store(Request $request, Property $property)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'nullable|string|max:20',
        ]);

        // Verifica disponibilidade
        if (!$property->isAvailable($request->check_in, $request->check_out)) {
            return response()->json([
                'message' => 'Datas indisponíveis'
            ], 422);
        }

        $reservation = PropertyReservation::create([
            'property_id' => $property->id,
            'user_id' => auth()->id(), // será null se não estiver logado
            'guest_name' => $request->guest_name,
            'guest_email' => $request->guest_email,
            'guest_phone' => $request->guest_phone,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'origin' => 'site',
            'status' => 'pending',
            'cleaning_fee' => $property->cleaning_fee ?? 0,
        ]);

        return response()->json([
            'message' => 'Pré-reserva criada',
            'reservation_id' => $reservation->id
        ]);
    }
}

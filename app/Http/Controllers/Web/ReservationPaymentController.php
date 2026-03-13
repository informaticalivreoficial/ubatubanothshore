<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PropertyReservation;
use Illuminate\Http\Request;

class ReservationPaymentController extends Controller
{
    public function success(PropertyReservation $reservation)
    {
        return view('web.reservation.success', compact('reservation'));
    }

    public function cancel(PropertyReservation $reservation)
    {
        return view('web.reservation.cancel', compact('reservation'));
    }

    public function pending(PropertyReservation $reservation)
    {
        return view('web.reservation.pending', compact('reservation'));
    }
}

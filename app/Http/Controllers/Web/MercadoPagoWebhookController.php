<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PropertyReservation;
use App\Models\User;
use App\Notifications\ReservationConfirmedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;

class MercadoPagoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $type = $request->input('type') ?? $request->input('topic');

        if ($type !== 'payment') {
            return response()->json(['ok' => true]);
        }

        $paymentId = $request->input('data.id') ?? $request->input('id');

        if (!$paymentId) {
            return response()->json(['error' => 'no payment id'], 400);
        }

        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));

        $client = new PaymentClient();

        try {
            $payment = $client->get($paymentId);
        } catch (\Exception $e) {
            Log::error("Webhook MP: erro ao buscar pagamento {$paymentId} - " . $e->getMessage());
            return response()->json(['error' => 'mp api error'], 500);
        }

        Log::info('MP Payment status', [
            'id'     => $payment->id,
            'status' => $payment->status,
        ]);

        $reservation = PropertyReservation::find($payment->external_reference);

        if (!$reservation) {
            Log::warning("Webhook MP: reserva não encontrada para payment {$paymentId}");
            return response()->json(['error' => 'reservation not found'], 404);
        }

        if ($payment->status === 'approved') {
            $reservation->update([
                'status'     => 'paid',
                'payment_id' => $payment->id,
            ]);

            User::where('admin', true)
                ->orWhere('superadmin', true)
                ->get()
                ->each->notify(new ReservationConfirmedNotification($reservation));
        }

        if ($payment->status === 'rejected') {
            $reservation->update(['status' => 'cancelled']);
        }

        if (in_array($payment->status, ['refunded', 'charged_back'])) {
            $reservation->update(['status' => 'refunded']);
        }

        if (!in_array($payment->status, ['approved', 'rejected', 'refunded', 'charged_back'])) {
            Log::info("MP status não tratado: {$payment->status} | Reserva: {$reservation->id}");
        }

        return response()->json(['ok' => true]);
    }
}

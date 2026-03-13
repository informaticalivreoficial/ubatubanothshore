<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PropertyReservation;
use Illuminate\Http\Request;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;

class MercadoPagoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        \Log::info('MP Webhook recebido', $request->all());

        // Valida assinatura (recomendado em produção)
        // $this->validateSignature($request);

        $type = $request->input('type') ?? $request->input('topic');

        if ($type !== 'payment') {
            return response()->json(['ok' => true]);
        }

        $paymentId = $request->input('data.id') ?? $request->input('id');

        if (!$paymentId) {
            return response()->json(['error' => 'no payment id'], 400);
        }

        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));

        $client  = new PaymentClient();
        $payment = $client->get($paymentId);

        \Log::info('MP Payment status', [
            'id'     => $payment->id,
            'status' => $payment->status,
        ]);

        // Busca reserva pelo external_reference
        $reservation = PropertyReservation::find($payment->external_reference);

        if (!$reservation) {
            return response()->json(['error' => 'reservation not found'], 404);
        }

        match ($payment->status) {
            'approved' => $reservation->update(['status' => 'paid']),
            'rejected' => $reservation->update(['status' => 'cancelled']),
            'refunded' => $reservation->update(['status' => 'refunded']),
            default    => null,
        };

        return response()->json(['ok' => true]);
    }
}

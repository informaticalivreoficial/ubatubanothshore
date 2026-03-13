<?php

namespace App\Livewire\Web;

use App\Models\PropertyReservation;
use App\Models\User;
use Livewire\Component;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\Client\Payment\PaymentClient;

class ReservationForm extends Component
{
    public $reservation;

    public $guest_name;
    public $guest_cpf;
    public $guest_phone;
    public $guest_email;

    public $guests = [];

    public $paymentStatus = null;
    public $pixQrCode = null;
    public $pixQrCodeBase64 = null; 

    public bool $showPayment = false;

    protected function rules()
    {
        return [
            'guest_name'  => 'required|min:3',
            'guest_cpf'   => 'required|cpf',
            'guest_phone' => 'required',
            'guest_email' => 'required|email',
            'guests.*.name' => 'required',
            'guests.*.cpf' => 'required',
            'guests.*.birthdate' => 'required|date',
        ];
    }

    protected function messages()
    {
        return [
            'guest_name.required' => 'O nome é obrigatório.',
            'guest_cpf.required' => 'O CPF é obrigatório.',
            'guest_email.email' => 'Informe um email válido.',
            'guests.*.name.required' => 'O nome do hóspede é obrigatório.',
            'guests.*.cpf.required' => 'O CPF do hóspede é obrigatório.',
            'guests.*.birthdate.required' => 'A data de nascimento é obrigatória.',
        ];
    }    

    public function mount($token)
    {
        $this->reservation = PropertyReservation::where('review_token', $token)->firstOrFail();

        // impedir reserva duplicada
        if ($this->reservation->status === 'finished') {
            abort(403, 'Reserva já finalizada!');
        }

        if($this->reservation->user->email === $this->reservation->guest_email 
            && $this->reservation->user->cpf !== null) {
            $this->guest_cpf = $this->reservation->user->cpf;
            $this->dispatch('disable-cpf');
        }

        // titular
        $this->guest_name  = $this->reservation->guest_name;
        $this->guest_phone = $this->reservation->guest_phone;
        $this->guest_email = $this->reservation->guest_email;

        // cria os hóspedes dinamicamente
        // hóspedes adicionais
        $totalGuests = $this->reservation->guests;

        for ($i = 1; $i < $totalGuests; $i++) {
            $this->guests[$i] = [
                'name' => '',
                'cpf' => '',
                'birthdate' => '',
            ];
        }
    }

    public function preparePayment()
    {
        $this->validate();

        User::where('email', $this->guest_email)->update([
            'cpf' => preg_replace('/\D/', '', $this->guest_cpf),
        ]);

        $this->reservation->update([
            'guests_info' => $this->guests,
            'status'      => 'waiting_payment',
            'expired_at'  => now()->addMinutes(30),
        ]);

        $this->showPayment = true;
    }

    public function processPayment(array $formData)
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));

        $client = new PaymentClient();

        try {
            $payment = $client->create([
                'transaction_amount' => (float) $this->reservation->total_value,
                'external_reference' => (string) $this->reservation->id, 
                'description'        => 'Reserva: ' . $this->reservation->property->title,
                'payment_method_id'  => $formData['payment_method_id'],
                
                'issuer_id'          => $formData['issuer_id'] ?? null,
                'token'              => $formData['token'] ?? null,
                'installments'       => $formData['installments'] ?? 1,
                'payer' => [
                    'email'      => $this->guest_email,
                    'first_name' => $this->guest_name,
                    'identification' => [
                        'type'   => 'CPF',
                        'number' => preg_replace('/\D/', '', $this->guest_cpf),
                    ]
                ],
                ...($formData['payment_method_id'] === 'pix' ? [
                    'date_of_expiration' => now()->addMinutes(30)->toIso8601String(),
                ] : []),
            ]);

            $this->paymentStatus = $payment->status;

            if ($payment->status === 'approved') {
                $this->reservation->update(['status' => 'paid']);
                $this->redirect(route('web.reservation.success', $this->reservation->id));
            }

            if ($payment->status === 'pending') {
                $this->pixQrCode       = $payment->point_of_interaction->transaction_data->qr_code;
                $this->pixQrCodeBase64 = $payment->point_of_interaction->transaction_data->qr_code_base64;
                $this->reservation->update(['status' => 'waiting_payment']);
            }

        } catch (MPApiException $e) {            
            logger($e->getApiResponse()->getContent());

            dd($e->getApiResponse()->getContent());
        }
    }

    public function render()
    {
        return view('livewire.web.reservation-form')->layout('web.layouts.app');
    }
}

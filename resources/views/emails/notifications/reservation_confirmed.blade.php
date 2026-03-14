@component('mail::message')
    # Reserva Confirmada!

    Olá,

    O pagamento da reserva foi confirmado com sucesso.

    **Cliente:** {{ $reservation->guest_name }}
    **Email:** {{ $reservation->guest_email }}
    **Telefone:** {{ $reservation->guest_phone }}

    **Imóvel:** {{ $reservation->property->title }}
    **Check-in:** {{ \Carbon\Carbon::parse($reservation->check_in)->format('d/m/Y') }}
    **Check-out:** {{ \Carbon\Carbon::parse($reservation->check_out)->format('d/m/Y') }}
    **Noites:** {{ $reservation->nights }}

    **Total pago:** R$ {{ number_format($reservation->total_value, 2, ',', '.') }}

@component('mail::button', ['url' => url('/admin/reservas/' . $reservation->id . '/editar')])
    Ver Reserva
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
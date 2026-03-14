@component('mail::message')
    # Nova Reserva Recebida!

    Olá,

    Uma nova reserva foi feita no site.

    **Cliente:** {{ $reservation->guest_name }}  
    **Email:** {{ $reservation->guest_email }}  
    **Telefone:** {{ $reservation->guest_phone }}  

    **Check-in:** {{ \Carbon\Carbon::parse($reservation->check_in)->format('d/m/Y') }}  
    **Check-out:** {{ \Carbon\Carbon::parse($reservation->check_out)->format('d/m/Y') }}  

    **Total:** R$ {{ number_format($reservation->total_value, 2, ',', '.') }}

@component('mail::button', ['url' => url('/admin/reservas/' . $reservation->id . '/editar')])
    Ver Reserva
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
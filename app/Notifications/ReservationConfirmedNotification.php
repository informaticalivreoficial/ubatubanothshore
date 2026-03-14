<?php

namespace App\Notifications;

use App\Models\PropertyReservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationConfirmedNotification extends Notification
{
    use Queueable;

    public $reservation;

    public function __construct(PropertyReservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->markdown('emails.notifications.reservation_confirmed', [
                'reservation' => $this->reservation
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message'        => 'Reserva confirmada',
            'url'            => url('/admin/reservas/' . $this->reservation->id . '/editar'),
            'reservation_id' => $this->reservation->id,
            'guest_name'     => $this->reservation->guest_name,
            'check_in'       => $this->reservation->check_in,
            'check_out'      => $this->reservation->check_out,
        ];
    }
}

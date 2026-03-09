<?php

namespace App\Notifications;

use App\Models\PropertyReservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReservationNotification extends Notification
{
    use Queueable;

    public $reservation;

    public function __construct(PropertyReservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // envia e-mail e salva no banco
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                ->markdown('emails.notifications.new_reservation', [
                    'reservation' => $this->reservation
                ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Nova reserva',
            'url' => url('/admin/reservas/' . $this->reservation->id . '/editar'),
            'reservation_id' => $this->reservation->id,
            'guest_name' => $this->reservation->guest_name,
            'check_in' => $this->reservation->check_in,
            'check_out' => $this->reservation->check_out,
        ];
    }
}

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
            'type' => 'ReservationCreated',
            'title' => 'Nova reserva recebida',
            'message' => "Você tem uma nova reserva de {$this->reservation->guest_name}",
            'description' => "A reserva está aguardando confirmação.",
            'color' => 'info',
            'url' => route('reservations.edit', $this->reservation)
        ];
    }
}

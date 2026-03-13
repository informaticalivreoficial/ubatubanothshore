<?php

namespace App\Mail;

use App\Models\Property;
use App\Models\PropertyReservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationFormLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    private $reservation;
    private $property;

    /**
     * Create a new message instance.
     */
    public function __construct(PropertyReservation $reservation)
    {
        $this->reservation = $reservation;
        $this->property = Property::where('id', $this->reservation->property_id)->first();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Finalizar Reserva',  
            from: new Address(env('MAIL_FROM_ADDRESS'), env('APP_NAME')), // Remetente
            to: [new Address($this->reservation['guest_email'], $this->reservation['guest_name'])], // Destinatário                
            replyTo: [
                new Address(env('MAIL_FROM_ADDRESS'), env('APP_NAME')),
            ],
            //bcc: env('MAIL_FROM_ADDRESS'), // Cópia oculta (opcional)
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.link-form-reserva',
            with:[
                'reservation' => $this->reservation,
                'property' => $this->property
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

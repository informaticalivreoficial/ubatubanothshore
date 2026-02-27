<?php

namespace App\Mail;

use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Consulta extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ consulta de imóvel via site',  
            from: new Address(env('MAIL_FROM_ADDRESS'), env('APP_NAME')), // Remetente
            to: [new Address(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))], // Destinatário                
            replyTo: [
                new Address($this->data['email'], $this->data['nome']),
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $property = Property::where('reference', $this->data['reference'])->first();
        return new Content(
            markdown: 'emails.consulta',
            with:[
                'nome' => $this->data['nome'],
                'email' => $this->data['email'],
                'property' => $property
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

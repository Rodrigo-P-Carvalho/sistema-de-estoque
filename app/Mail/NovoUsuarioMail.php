<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NovoUsuarioMail extends Mailable
{
    use Queueable, SerializesModels;


    public $nome;
    public $senhaTemporaria;
    /**
     * Create a new message instance.
     */
    public function __construct($nome,$senhaTemporaria)
    {
        $this->nome = $nome;
        $this->senhaTemporaria = $senhaTemporaria;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bem-vindo ao AutoPeças - Seu acesso foi liberado!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<h1>Olá, {$this->nome}!</h1>
                         <p>Você foi convidado para acessar o sistema AutoPeças.</p>
                         <p>Sua senha temporária é: <strong>{$this->senhaTemporaria}</strong></p>
                         <p>Por favor, acesse o sistema e troque sua senha imediatamente.</p>"
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

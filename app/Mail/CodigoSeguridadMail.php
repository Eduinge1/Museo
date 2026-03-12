<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CodigoSeguridadMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $codigo;
    public string $nombre;

    public function __construct(string $codigo, string $nombre)
    {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔐 Tu Código de Seguridad — Museo de Arte Contemporáneo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.codigo_seguridad',
        );
    }
}
<?php

namespace App\Emails\Mails;

use App\Emails\MailServiceManager;

/**
 * Clase de correo especializada para enviar el código de seguridad al usuario.
 *
 * Principio de Responsabilidad Única (S en SOLID):
 * Su única responsabilidad es construir y enviar el correo
 * de código de seguridad. No gestiona instancias de servicios
 * ni lógica de negocio.
 *
 * Principio de Inversión de Dependencias (D en SOLID):
 * Accede al servicio a través del MailServiceManager, que a su vez
 * depende de la abstracción MailServiceInterface.
 */
class CodigoSeguridadMail
{
    public function __construct(
        private readonly string $codigo,
        private readonly string $nombreUsuario,
    ) {}

    /**
     * Envía el correo de código de seguridad.
     *
     * @param  string  $toEmail  Dirección destino.
     * @return bool
     */
    public function send(string $toEmail): bool
    {
        $service = MailServiceManager::getInstance()->service();

        return $service->send(
            to:      $toEmail,
            subject: 'Tu nuevo código de seguridad',
            body:    $this->buildBody(),
            options: [
                'plain_text' => $this->buildPlainText(),
            ]
        );
    }

    // -------------------------------------------------------------------------
    // Construcción del contenido del correo
    // -------------------------------------------------------------------------

    /**
     * Genera el cuerpo HTML del correo.
     */
    private function buildBody(): string
    {
        return <<<HTML
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Código de Seguridad</title>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
                .header { background-color: #1a73e8; padding: 30px; text-align: center; }
                .header h1 { color: #ffffff; margin: 0; font-size: 22px; }
                .body { padding: 30px; color: #333333; }
                .code-box { background-color: #f0f4ff; border: 2px dashed #1a73e8; border-radius: 8px; text-align: center; padding: 20px; margin: 24px 0; }
                .code-box span { font-size: 36px; font-weight: bold; letter-spacing: 8px; color: #1a73e8; }
                .footer { background-color: #f4f4f4; text-align: center; padding: 16px; font-size: 12px; color: #999999; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🔐 Código de Seguridad</h1>
                </div>
                <div class="body">
                    <p>Hola, <strong>{$this->nombreUsuario}</strong>.</p>
                    <p>Tu solicitud de recuperación fue verificada exitosamente. Aquí está tu nuevo código de seguridad:</p>
                    <div class="code-box">
                        <span>{$this->codigo}</span>
                    </div>
                    <p>Por seguridad, <strong>no compartas este código</strong> con nadie.</p>
                    <p>Si no solicitaste este código, ignora este mensaje o contacta a soporte.</p>
                </div>
                <div class="footer">
                    Este correo fue generado automáticamente, por favor no respondas a este mensaje.
                </div>
            </div>
        </body>
        </html>
        HTML;
    }

    /**
     * Genera la versión en texto plano del correo (fallback).
     */
    private function buildPlainText(): string
    {
        return <<<TEXT
        Hola, {$this->nombreUsuario}.

        Tu solicitud de recuperación fue verificada exitosamente.
        Tu nuevo código de seguridad es: {$this->codigo}

        Por seguridad, no compartas este código con nadie.
        Si no solicitaste este código, ignora este mensaje o contacta a soporte.
        TEXT;
    }
}
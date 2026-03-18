<?php

namespace App\Emails\Services;

use App\Emails\Contracts\MailServiceInterface;

/**
 * Implementación concreta del servicio de correo usando Gmail (SMTP/API).
 *
 * NOTA: Esta es una implementación base/stub que demuestra
 * la extensibilidad del sistema. Implementa el mismo contrato
 * que SendGridMailService sin modificar ninguna otra clase.
 *
 * Para completar la integración real, se debe implementar la
 * lógica de conexión SMTP o la API de Gmail aquí.
 */
class GmailMailService implements MailServiceInterface
{
    private string $apiKey    = '';
    private string $fromEmail = '';
    private string $fromName  = '';

    /**
     * {@inheritDoc}
     */
    public function config(array $config): static
    {
        $this->apiKey    = $config['api_key']    ?? env('API_KEY_SERVICE_MAIL', '');
        $this->fromEmail = $config['from_email'] ?? env('MAIL_FROM_ADDRESS', '');
        $this->fromName  = $config['from_name']  ?? env('MAIL_FROM_NAME', 'No Reply');

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * TODO: Implementar la lógica de envío con la API/SMTP de Gmail.
     */
    public function send(string $to, string $subject, string $body, array $options = []): bool
    {
        // Implementar integración con Gmail aquí.
        throw new \RuntimeException(
            'GmailMailService: el método send() aún no está implementado. ' .
            'Por favor completa la integración con la API de Gmail.'
        );
    }
}
<?php

namespace App\Emails\Services;

use App\Emails\Contracts\MailServiceInterface;

/**
 * Implementación concreta del servicio de correo usando SendGrid.
 *
 * Principio de Sustitución de Liskov (L en SOLID):
 * Esta clase puede sustituir a MailServiceInterface en cualquier
 * parte del sistema sin romper el comportamiento esperado.
 *
 * Principio de Responsabilidad Única (S en SOLID):
 * Su única responsabilidad es comunicarse con la API de SendGrid.
 */
class SendGridMailService implements MailServiceInterface
{
    /** URL base de la API de SendGrid. */
    private const API_URL = 'https://api.sendgrid.com/v3/mail/send';

    private string $apiKey      = '';
    private string $fromEmail   = '';
    private string $fromName    = '';

    // -------------------------------------------------------------------------
    // Implementación del contrato
    // -------------------------------------------------------------------------

    /**
     * Configura las credenciales y parámetros del servicio SendGrid.
     *
     * Claves esperadas en $config:
     *  - api_key    (string) – API Key de SendGrid. Por defecto usa API_KEY_SERVICE_MAIL del .env
     *  - from_email (string) – Email remitente. Por defecto usa MAIL_FROM_ADDRESS del .env
     *  - from_name  (string) – Nombre remitente. Por defecto usa MAIL_FROM_NAME del .env
     *
     * @param  array<string, mixed>  $config
     * @return static
     */
    public function config(array $config): static
    {
        $this->apiKey    = $config['api_key']    ?? env('API_KEY_SERVICE_MAIL', '');
        $this->fromEmail = $config['from_email'] ?? env('MAIL_FROM_ADDRESS', '');
        $this->fromName  = $config['from_name']  ?? env('MAIL_FROM_NAME', 'No Reply');

        if (empty($this->apiKey)) {
            throw new \RuntimeException(
                'SendGrid requiere una API Key. ' .
                'Define API_KEY_SERVICE_MAIL en tu .env o pásala en el array de config.'
            );
        }

        if (empty($this->fromEmail)) {
            throw new \RuntimeException(
                'SendGrid requiere un email remitente. ' .
                'Define MAIL_FROM_ADDRESS en tu .env o pásalo en el array de config.'
            );
        }

        return $this;
    }

    /**
     * Envía un correo electrónico a través de la API HTTP de SendGrid.
     *
     * @param  string  $to       Dirección destino
     * @param  string  $subject  Asunto
     * @param  string  $body     Cuerpo HTML del correo
     * @param  array<string, mixed>  $options  Opciones adicionales opcionales:
     *                           - 'plain_text' (string) Versión texto plano
     *                           - 'reply_to'   (string) Email de respuesta
     * @return bool
     */
    public function send(string $to, string $subject, string $body, array $options = []): bool
    {
        $payload = $this->buildPayload($to, $subject, $body, $options);

        $response = $this->makeRequest($payload);

        return $response['success'];
    }

    // -------------------------------------------------------------------------
    // Métodos privados de apoyo
    // -------------------------------------------------------------------------

    /**
     * Construye el payload JSON que espera la API de SendGrid.
     *
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    private function buildPayload(string $to, string $subject, string $body, array $options): array
    {
        $content = [
            ['type' => 'text/html', 'value' => $body],
        ];

        // Si se pasa texto plano, se añade como versión alternativa
        if (!empty($options['plain_text'])) {
            array_unshift($content, [
                'type'  => 'text/plain',
                'value' => $options['plain_text'],
            ]);
        }

        $payload = [
            'personalizations' => [
                [
                    'to'      => [['email' => $to]],
                    'subject' => $subject,
                ],
            ],
            'from'    => [
                'email' => $this->fromEmail,
                'name'  => $this->fromName,
            ],
            'content' => $content,
        ];

        // Campo opcional reply_to
        if (!empty($options['reply_to'])) {
            $payload['reply_to'] = ['email' => $options['reply_to']];
        }

        return $payload;
    }

    /**
     * Realiza la petición HTTP a la API de SendGrid usando cURL.
     *
     * @param  array<string, mixed>  $payload
     * @return array{success: bool, status: int, body: string}
     */
    private function makeRequest(array $payload): array
    {
        $ch = curl_init(self::API_URL);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
            ],
        ]);

        $responseBody = curl_exec($ch);
        $httpStatus   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError    = curl_error($ch);

        curl_close($ch);

        if ($curlError) {
            \Log::error('[SendGridMailService] cURL error: ' . $curlError);
            return ['success' => false, 'status' => 0, 'body' => $curlError];
        }

        // SendGrid retorna 202 Accepted en éxito
        $success = $httpStatus >= 200 && $httpStatus < 300;

        if (!$success) {
            \Log::error("[SendGridMailService] Error HTTP {$httpStatus}: {$responseBody}");
        }

        return [
            'success' => $success,
            'status'  => $httpStatus,
            'body'    => $responseBody,
        ];
    }
}
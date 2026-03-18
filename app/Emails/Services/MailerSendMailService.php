<?php

namespace App\Emails\Services;

use App\Emails\Contracts\MailServiceInterface;
use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;
use MailerSend\Exceptions\MailerSendValidationException;
use MailerSend\Exceptions\MailerSendRateLimitException;

/**
 * Implementación concreta del servicio de correo usando MailerSend.
 *
 * Usa el SDK oficial: mailersend/mailersend
 * Instalación: composer require mailersend/mailersend php-http/guzzle7-adapter nyholm/psr7
 *
 * Principio de Responsabilidad Única (S en SOLID):
 * Su única responsabilidad es comunicarse con la API de MailerSend.
 *
 * Principio de Sustitución de Liskov (L en SOLID):
 * Puede sustituir a cualquier otro MailServiceInterface sin romper el sistema.
 */
class MailerSendMailService implements MailServiceInterface
{
    private string $apiKey    = '';
    private string $fromEmail = '';
    private string $fromName  = '';

    /** Instancia del SDK de MailerSend, se crea una vez en config(). */
    private ?MailerSend $client = null;

    // -------------------------------------------------------------------------
    // Implementación del contrato
    // -------------------------------------------------------------------------

    /**
     * Configura las credenciales del servicio MailerSend.
     *
     * Claves esperadas en $config:
     *  - api_key    (string) – Token de MailerSend. Por defecto usa API_KEY_SERVICE_MAIL del .env
     *  - from_email (string) – Email remitente verificado en MailerSend. Por defecto MAIL_FROM_ADDRESS
     *  - from_name  (string) – Nombre remitente. Por defecto MAIL_FROM_NAME
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
                'MailerSend requiere una API Key. ' .
                'Define API_KEY_SERVICE_MAIL en tu .env o pásala en el array de config.'
            );
        }

        if (empty($this->fromEmail)) {
            throw new \RuntimeException(
                'MailerSend requiere un email remitente verificado. ' .
                'Define MAIL_FROM_ADDRESS en tu .env o pásalo en el array de config.'
            );
        }

        // Instanciar el cliente del SDK una sola vez al configurar.
        // El SDK lee MAILERSEND_API_KEY del entorno automáticamente,
        // pero lo pasamos explícitamente para usar nuestra variable API_KEY_SERVICE_MAIL.
        $this->client = new MailerSend(['api_key' => $this->apiKey]);

        return $this;
    }

    /**
     * Envía un correo electrónico a través del SDK de MailerSend.
     *
     * @param  string  $to       Dirección destino
     * @param  string  $subject  Asunto
     * @param  string  $body     Cuerpo HTML del correo
     * @param  array<string, mixed>  $options  Opciones adicionales:
     *                           - 'plain_text'     (string) Versión texto plano
     *                           - 'reply_to'       (string) Email de respuesta
     *                           - 'reply_to_name'  (string) Nombre de respuesta
     * @return bool
     */
    public function send(string $to, string $subject, string $body, array $options = []): bool
    {
        if ($this->client === null) {
            throw new \RuntimeException(
                'MailerSendMailService no ha sido configurado. ' .
                'Llama a config() antes de usar send().'
            );
        }

        try {
            $recipients = [
                new Recipient($to, $this->fromEmail),
            ];

            $emailParams = (new EmailParams())
                ->setFrom($this->fromEmail)
                ->setFromName($this->fromName)
                ->setRecipients($recipients)
                ->setSubject($subject)
                ->setHtml($body);

            // Texto plano opcional (fallback para clientes de correo que no soportan HTML)
            if (!empty($options['plain_text'])) {
                $emailParams->setText($options['plain_text']);
            }

            // Reply-To opcional
            if (!empty($options['reply_to'])) {
                $emailParams->setReplyTo($options['reply_to']);

                if (!empty($options['reply_to_name'])) {
                    $emailParams->setReplyToName($options['reply_to_name']);
                }
            }

            $this->client->email->send($emailParams);

            return true;

        } catch (MailerSendValidationException $e) {
            error_log('[MailerSendMailService] Error de validación: ' . $e->getMessage() . ' | Errors: ' . json_encode($e->getErrors()) . ' | Status: ' . $e->getStatusCode());
            return false;

        } catch (MailerSendRateLimitException $e) {
            error_log('[MailerSendMailService] Rate limit alcanzado: ' . $e->getMessage());
            return false;

        } catch (\Throwable $e) {
            error_log('[MailerSendMailService] Error inesperado: ' . $e->getMessage());
            return false;
        }
    }
}
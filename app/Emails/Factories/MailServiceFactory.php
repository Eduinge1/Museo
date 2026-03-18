<?php

namespace App\Emails\Factories;

use App\Emails\Contracts\MailServiceFactoryInterface;
use App\Emails\Contracts\MailServiceInterface;
use App\Emails\Enums\MailProvider;
use App\Emails\Services\GmailMailService;
use App\Emails\Services\MailerSendMailService;
use App\Emails\Services\SendGridMailService;

/**
 * Factory concreto que resuelve la implementación de correo correcta
 * según la variable de entorno NAME_SERVICE_MAIL.
 *
 * Principio Abierto/Cerrado (O en SOLID):
 * Para añadir un nuevo proveedor sólo hay que:
 *  1. Crear la nueva clase en Services/ implementando MailServiceInterface.
 *  2. Añadir el caso al enum MailProvider.
 *  3. Añadir el case aquí.
 * No se modifica ninguna otra parte del sistema.
 *
 * Principio de Sustitución de Liskov (L en SOLID):
 * Cada servicio retornado puede usarse en cualquier lugar que
 * espere un MailServiceInterface sin romper el contrato.
 */
class MailServiceFactory implements MailServiceFactoryInterface
{
    /**
     * Resuelve la implementación concreta del servicio de correo.
     *
     * Lee NAME_SERVICE_MAIL del .env, valida contra el enum MailProvider
     * y retorna la instancia correspondiente ya configurada.
     *
     * @return MailServiceInterface
     *
     * @throws \InvalidArgumentException Si el proveedor no está soportado.
     */
    public function resolve(): MailServiceInterface
    {
        $provider = MailProvider::fromEnv();

        $service = match ($provider) {
            MailProvider::SENDGRID   => new SendGridMailService(),
            MailProvider::GMAIL      => new GmailMailService(),
            MailProvider::MAILERSEND => new MailerSendMailService(),
        };

        // Configuración automática con los valores del .env.
        // El array vacío hace que config() use los valores de entorno por defecto.
        return $service->config([]);
    }
}
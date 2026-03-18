<?php

namespace App\Emails\Enums;

/**
 * Enum que centraliza y tipifica los proveedores de correo soportados.
 *
 * Principio de Responsabilidad Única (S en SOLID):
 * Su única responsabilidad es conocer los proveedores válidos
 * y ofrecer una forma segura de instanciarlos desde un string.
 */
enum MailProvider: string
{
    case SENDGRID    = 'sendgrid';
    case GMAIL       = 'gmail';
    case MAILERSEND  = 'mailersend';

    /**
     * Intenta crear una instancia del enum desde el valor de la variable
     * de entorno. Lanza excepción si el valor no es soportado.
     *
     * @throws \InvalidArgumentException
     */
    public static function fromEnv(): self
    {
        $name = strtolower(trim(env('NAME_SERVICE_MAIL', '')));

        if (empty($name)) {
            throw new \InvalidArgumentException(
                'La variable de entorno NAME_SERVICE_MAIL no está definida.'
            );
        }

        $provider = self::tryFrom($name);

        if ($provider === null) {
            $supported = implode(', ', array_column(self::cases(), 'value'));
            throw new \InvalidArgumentException(
                "El servicio de correo \"{$name}\" no está soportado. " .
                "Proveedores disponibles: {$supported}."
            );
        }

        return $provider;
    }
}
<?php

namespace App\Emails;

use App\Emails\Contracts\MailServiceFactoryInterface;
use App\Emails\Contracts\MailServiceInterface;
use App\Emails\Factories\MailServiceFactory;

/**
 * Singleton que gestiona la instancia única del servicio de correo.
 *
 * Responsabilidades:
 *  - Garantizar que sólo exista UNA instancia del servicio en toda la aplicación.
 *  - Delegar la creación del servicio concreto al Factory (desacoplamiento).
 *  - Exponer el servicio resuelto para el resto de la aplicación.
 *
 * Principio de Responsabilidad Única (S en SOLID):
 * Su única responsabilidad es gestionar el ciclo de vida de la instancia.
 * No sabe nada de cómo funciona SendGrid, Gmail, etc.
 *
 * Principio de Inversión de Dependencias (D en SOLID):
 * Depende de MailServiceFactoryInterface, no de MailServiceFactory directamente,
 * lo que permite inyectar un factory diferente en tests u otros contextos.
 */
final class MailServiceManager
{
    /** Instancia única del manager (Singleton). */
    private static ?self $instance = null;

    /** Instancia resuelta del servicio de correo concreto. */
    private MailServiceInterface $resolvedService;

    /**
     * Constructor privado: impide instanciación externa.
     * Recibe el factory por inyección para poder sustituirlo en tests.
     */
    private function __construct(MailServiceFactoryInterface $factory)
    {
        // El factory decide qué implementación concreta retornar.
        $this->resolvedService = $factory->resolve();
    }

    /** Bloquear clonación del Singleton. */
    private function __clone() {}

    /** Bloquear deserialización del Singleton. */
    public function __wakeup()
    {
        throw new \RuntimeException('No se puede deserializar un Singleton.');
    }

    // -------------------------------------------------------------------------
    // Punto de acceso global
    // -------------------------------------------------------------------------

    /**
     * Retorna la instancia única del manager.
     *
     * Si no existe, la crea usando el factory proporcionado (o el por defecto).
     * Si ya existe, simplemente devuelve la instancia almacenada.
     *
     * @param  MailServiceFactoryInterface|null  $factory  Inyección opcional para tests.
     * @return static
     */
    public static function getInstance(?MailServiceFactoryInterface $factory = null): static
    {
        if (static::$instance === null) {
            // Primera llamada: crear la instancia con el factory resuelto.
            static::$instance = new static($factory ?? new MailServiceFactory());
        }

        return static::$instance;
    }

    /**
     * Retorna el servicio de correo concreto ya resuelto y configurado.
     *
     * @return MailServiceInterface
     */
    public function service(): MailServiceInterface
    {
        return $this->resolvedService;
    }

    // -------------------------------------------------------------------------
    // Utilidad para tests: permite reiniciar el Singleton entre pruebas
    // -------------------------------------------------------------------------

    /**
     * Reinicia la instancia del Singleton.
     * USO EXCLUSIVO EN TESTS — no llamar en producción.
     *
     * @internal
     */
    public static function reset(): void
    {
        static::$instance = null;
    }
}
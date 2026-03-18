<?php

namespace App\Emails\Providers;

use App\Emails\Contracts\MailServiceFactoryInterface;
use App\Emails\Contracts\MailServiceInterface;
use App\Emails\Factories\MailServiceFactory;
use App\Emails\MailServiceManager;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider del módulo de emails.
 *
 * Registra las abstracciones e implementaciones en el contenedor
 * de servicios de Laravel, habilitando la inyección de dependencias
 * en cualquier parte de la aplicación.
 *
 * Para activarlo, añadir a config/app.php en 'providers':
 *   App\Emails\Providers\MailServiceProvider::class,
 */
class MailServiceProvider extends ServiceProvider
{
    /**
     * Registra los bindings del módulo en el contenedor.
     */
    public function register(): void
    {
        // Registrar el Factory como singleton en el contenedor.
        $this->app->singleton(MailServiceFactoryInterface::class, MailServiceFactory::class);

        // Registrar el Manager como singleton: una sola instancia en toda la app.
        $this->app->singleton(MailServiceManager::class, function () {
            return MailServiceManager::getInstance();
        });

        // Registrar el MailServiceInterface apuntando al servicio resuelto por el Manager.
        // Esto permite inyectar MailServiceInterface directamente en constructores.
        $this->app->singleton(MailServiceInterface::class, function ($app) {
            return $app->make(MailServiceManager::class)->service();
        });
    }

    /**
     * Acciones a ejecutar tras registrar todos los providers.
     */
    public function boot(): void
    {
        // Aquí se pueden publicar configuraciones, vistas, etc., si se necesita en el futuro.
    }
}
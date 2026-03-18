<?php

namespace App\Emails\Contracts;

/**
 * Contrato para el Factory de servicios de correo.
 *
 * Principio Abierto/Cerrado (O en SOLID):
 * Si en el futuro se necesita un nuevo proveedor,
 * sólo se añade un nuevo case en el Factory sin
 * modificar el código existente.
 */
interface MailServiceFactoryInterface
{
    /**
     * Resuelve y retorna la implementación concreta del servicio
     * de correo según la variable de entorno 'NAME_SERVICE_MAIL'.
     *
     * @return MailServiceInterface
     *
     * @throws \InvalidArgumentException Si el servicio no está soportado.
     */
    public function resolve(): MailServiceInterface;
}
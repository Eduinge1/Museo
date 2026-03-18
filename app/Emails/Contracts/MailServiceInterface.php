<?php

namespace App\Emails\Contracts;

/**
 * Contrato base que define las operaciones esenciales
 * que cualquier servicio de correo debe implementar.
 *
 * Principio de Inversión de Dependencias (D en SOLID):
 * El resto del sistema depende de esta abstracción,
 * no de implementaciones concretas (SendGrid, Gmail, etc.).
 */
interface MailServiceInterface
{
    /**
     * Configura el servicio con los parámetros necesarios
     * (API key, remitente por defecto, opciones, etc.).
     *
     * @param  array<string, mixed>  $config
     * @return static
     */
    public function config(array $config): static;

    /**
     * Envía un correo electrónico.
     *
     * @param  string  $to        Dirección destino
     * @param  string  $subject   Asunto del correo
     * @param  string  $body      Cuerpo del correo (HTML o texto plano)
     * @param  array<string, mixed>  $options  Opciones adicionales del proveedor
     * @return bool    true si fue enviado exitosamente
     */
    public function send(string $to, string $subject, string $body, array $options = []): bool;
}
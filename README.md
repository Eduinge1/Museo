**Proyecto**

- **Nombre:** Museo (proyecto de BD)

**Requisitos**

- **PHP:** 8.0 o superior
- **Composer:** 2.x (ejecuta `composer --version` para verificar)
- **Node.js:** 16 o superior (recomendado para Vite)
- **npm/yarn:** npm 8+ o yarn equivalente (para compilar assets)

**Instalación (local)**

1. Clona el repositorio y entra en la carpeta del proyecto:

```bash
git clone https://github.com/Eduinge1/Museo museo
cd museo
```

2. Instala dependencias PHP con Composer:

```bash
composer install
```

3. Copia el archivo de entorno y configura variables (base de datos, correo, etc.):

```bash
cp .env.example .env
# o en Windows: copy .env.example .env
```

4. Genera la clave de la aplicación:

```bash
php artisan key:generate
```

5. Ajusta permisos (si es necesario, para usuarios con linux, windows puedes obviar) para `storage` y `bootstrap/cache`:

```bash
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

6. Ejecuta migraciones y seeders si corresponde:

```bash
php artisan migrate --seed
```

7. Crea el enlace simbólico para el almacenamiento público:

```bash
php artisan storage:link
```

**Assets (Vite)**

Si vas a trabajar con los assets y Vite, instala las dependencias de Node y ejecuta el dev server o build:

```bash
npm ci
# o con yarn: yarn

# En desarrollo (hot reload):
npm run dev

# Para producción (compilar):
npm run build
```

**Ejecutar la aplicación**

```bash
php artisan serve --host=127.0.0.1 --port=8000
# luego abre http://127.0.0.1:8000
```

**Verificar versiones**

- PHP: `php -v`
- Composer: `composer --version`
- Node: `node -v`
- npm: `npm -v`

**Notas y recomendaciones**

- Asegúrate de configurar correctamente las variables de entorno en `.env` (conexión a BD, credenciales de correo, etc.).
- Revisa `php.ini` y extensiones requeridas (p. ej. `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`).
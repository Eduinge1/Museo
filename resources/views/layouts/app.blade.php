<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Museo de Arte Contemporáneo')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    /* Aquí pegué los colores de tu archivo original */
    :root { --accent-1: #FF4D6D; --accent-2: #FFBE0B; --accent-3: #3A86FF; --accent-4: #8338EC; --dark: #0D0D0D; --mid: #1A1A2E; --light: #F8F5F0; --card-bg: #ffffff; }
    body { font-family: 'DM Sans', sans-serif; background: var(--light); }
    .navbar { background: rgba(13, 13, 13, 0.95); backdrop-filter: blur(10px); padding: 1rem 0; }
    /* ... (puedes copiar el resto del <style> de tu archivo pantalla 1 aquí) ... */
  </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">MUSEO<span>.</span></a>
        </div>
    </nav>

    @yield('content')

    <footer class="py-4 text-center mt-5" style="background:#000; color:#fff;">
        <p>© 2025 Museo de Arte Contemporáneo</p>
    </footer>
</body>
</html>

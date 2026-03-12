<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>404 — Página no encontrada</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --accent-1: #FF4D6D; --accent-2: #FFBE0B; --accent-3: #3A86FF;
      --accent-4: #8338EC; --dark: #0D0D0D; --light: #F8F5F0;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: var(--dark); font-family: 'DM Sans', sans-serif; min-height: 100vh; display: flex; flex-direction: column; }

    /* NAVBAR */
    .navbar-mac { background: var(--dark); padding: 0.85rem 2rem; border-bottom: 2px solid var(--accent-1); display: flex; align-items: center; justify-content: space-between; }
    .navbar-brand-custom { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 900; color: #fff; letter-spacing: -0.5px; text-decoration: none; }
    .navbar-brand-custom span { color: var(--accent-2); }
    .btn-nav-back { background: transparent; color: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.2); border-radius: 50px; padding: 0.4rem 1.2rem; font-size: 0.875rem; text-decoration: none; transition: all 0.2s; font-family: 'DM Sans', sans-serif; }
    .btn-nav-back:hover { border-color: var(--accent-2); color: var(--accent-2); }

    /* PAGE BG */
    .page-bg { flex: 1; display: flex; align-items: center; justify-content: center; position: relative; padding: 3rem 1rem; }
    .page-bg::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 20% 30%, rgba(58,134,255,0.2) 0%, transparent 55%), radial-gradient(ellipse at 80% 70%, rgba(255,77,109,0.18) 0%, transparent 55%), radial-gradient(ellipse at 50% 50%, rgba(131,56,236,0.1) 0%, transparent 70%); pointer-events: none; }

    /* CARD */
    .error-card { background: #fff; color: var(--dark); border-radius: 24px; padding: 2.5rem; width: 100%; max-width: 520px; position: relative; z-index: 2; box-shadow: 0 24px 80px rgba(0,0,0,0.5); animation: slideUp 0.5s cubic-bezier(.22,.68,0,1.2); text-align: center; }
    @keyframes slideUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }

    .error-icon-wrap { width: 80px; height: 80px; background: linear-gradient(135deg, var(--accent-1), var(--accent-4)); border-radius: 22px; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; color: #fff; margin: 0 auto 1.5rem; }
    .error-code-label { font-size: 0.7rem; font-weight: 500; letter-spacing: 4px; text-transform: uppercase; color: var(--accent-1); margin-bottom: 0.4rem; }
    .error-code { font-family: 'Playfair Display', serif; font-size: 5rem; font-weight: 900; color: var(--dark); line-height: 1; margin-bottom: 0.5rem; }
    .error-title { font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 700; color: var(--dark); margin-bottom: 0.75rem; }
    .error-sub { font-size: 0.9rem; color: #777; line-height: 1.7; margin-bottom: 2rem; }

    .btn-primary { display: inline-flex; align-items: center; gap: 0.5rem; background: var(--dark); color: #fff; border-radius: 12px; padding: 0.85rem 2rem; font-size: 0.95rem; font-weight: 500; text-decoration: none; transition: background 0.2s; font-family: 'DM Sans', sans-serif; margin-right: 0.5rem; margin-bottom: 0.5rem; }
    .btn-primary:hover { background: var(--accent-3); color: #fff; }
    .btn-secondary { display: inline-flex; align-items: center; gap: 0.5rem; background: transparent; color: var(--dark); border: 1.5px solid var(--dark); border-radius: 12px; padding: 0.85rem 2rem; font-size: 0.95rem; font-weight: 500; text-decoration: none; transition: all 0.2s; font-family: 'DM Sans', sans-serif; margin-bottom: 0.5rem; }
    .btn-secondary:hover { background: var(--dark); color: #fff; }

    .error-divider { border: none; border-top: 1px solid #f0f0f0; margin: 1.5rem 0; }
    .error-hint { font-size: 0.78rem; color: #aaa; }
  </style>
</head>
<body>

  <nav class="navbar-mac">
    <a href="{{ url('/') }}" class="navbar-brand-custom"><span>Mac</span>Arte</a>
    <a href="{{ url('/') }}" class="btn-nav-back">← Volver al inicio</a>
  </nav>

  <div class="page-bg">
    <div class="error-card">

      <div class="error-icon-wrap">🔍</div>
      <div class="error-code-label">Error</div>
      <div class="error-code">404</div>
      <h1 class="error-title">Página no encontrada</h1>
      <p class="error-sub">
        La obra que buscas parece haberse desvanecido en el tiempo.<br>
        Es posible que la URL haya cambiado o que el contenido ya no exista.
      </p>

      <a href="{{ url('/') }}" class="btn-primary">🏠 Ir al inicio</a>
      <a href="javascript:history.back()" class="btn-secondary">← Volver atrás</a>

      <hr class="error-divider">
      <p class="error-hint">Si crees que esto es un error, por favor contáctanos.</p>

    </div>
  </div>

</body>
</html>

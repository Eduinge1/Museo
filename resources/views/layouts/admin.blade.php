<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin — Museo de Arte Contemporáneo')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    :root {
      --accent-1: #FF4D6D;
      --accent-2: #FFBE0B;
      --accent-3: #3A86FF;
      --accent-4: #8338EC;
      --dark: #0D0D0D;
      --mid: #1A1A2E;
      --light: #F8F5F0;
      --sidebar-w: 240px;
      --success: #06D6A0;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DM Sans', sans-serif; background: var(--light); color: var(--dark); min-height: 100vh; margin: 0; }

    /* ─── SIDEBAR ─── */
    .sidebar {
      width: var(--sidebar-w);
      background: var(--dark);
      height: 100vh;
      position: fixed;
      top: 0; left: 0;
      display: flex;
      flex-direction: column;
      z-index: 1000;
      border-right: 1px solid rgba(255,255,255,0.06);
    }
    .sidebar-logo {
      padding: 1.5rem 1.4rem 1.2rem;
      border-bottom: 1px solid rgba(255,255,255,0.07);
    }
    .sidebar-logo a { font-family: 'Playfair Display', serif; font-size: 1.3rem; font-weight: 900; color: #fff; text-decoration: none; letter-spacing: -0.5px; }
    .sidebar-logo a span { color: var(--accent-2); }
    .sidebar-logo .admin-pill {
      display: inline-block;
      background: rgba(131,56,236,0.25);
      border: 1px solid rgba(131,56,236,0.4);
      color: #c77dff;
      font-size: 0.62rem;
      font-weight: 600;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      border-radius: 50px;
      padding: 0.15rem 0.6rem;
      margin-top: 0.3rem;
    }

    .sidebar-nav { flex: 1; padding: 1rem 0.8rem; display: flex; flex-direction: column; gap: 0.2rem; overflow-y: auto; }
    .nav-section-label {
      font-size: 0.62rem;
      font-weight: 600;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: rgba(255,255,255,0.25);
      padding: 0.8rem 0.6rem 0.3rem;
    }
    .nav-item {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.6rem 0.8rem;
      border-radius: 10px;
      color: rgba(255,255,255,0.55);
      font-size: 0.875rem;
      font-weight: 500;
      text-decoration: none;
      transition: background 0.2s, color 0.2s;
      cursor: pointer;
    }
    .nav-item:hover { background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.9); }
    .nav-item.active { background: rgba(255,77,109,0.15); color: var(--accent-1); border: 1px solid rgba(255,77,109,0.2); }
    .nav-item i { font-size: 1rem; width: 18px; text-align: center; flex-shrink: 0; }

    .sidebar-footer {
      padding: 1rem 1.2rem;
      border-top: 1px solid rgba(255,255,255,0.07);
      background: var(--dark);
    }
    .admin-user-row {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    .admin-avatar {
      width: 34px; height: 34px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--accent-4), var(--accent-1));
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 0.8rem; font-weight: 700;
      flex-shrink: 0;
    }
    .admin-name { font-size: 0.82rem; color: #fff; font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 100px; }
    .admin-role { font-size: 0.7rem; color: rgba(255,255,255,0.4); }
    .btn-logout {
      margin-left: auto;
      background: none;
      border: none;
      color: rgba(255,255,255,0.35);
      font-size: 1rem;
      cursor: pointer;
      transition: color 0.2s;
      padding: 0;
    }
    .btn-logout:hover { color: var(--accent-1); }

    /* ─── MAIN CONTENT ─── */
    .main-content {
      padding-left: var(--sidebar-w);
      width: 100%;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* TOP BAR */
    .topbar {
      background: #fff;
      border-bottom: 1px solid #eee;
      padding: 0.9rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 50;
    }
    .topbar-title { font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; color: var(--dark); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .topbar-subtitle { font-size: 0.78rem; color: #999; margin-top: 1px; }
    .topbar-right { display: flex; align-items: center; gap: 1rem; }
    .topbar-date { font-size: 0.78rem; color: #aaa; }
    .btn-topbar-notif {
      position: relative;
      background: var(--light);
      border: 1px solid #eee;
      border-radius: 10px;
      width: 36px; height: 36px;
      display: flex; align-items: center; justify-content: center;
      color: #666; font-size: 1rem; cursor: pointer; transition: all 0.2s;
    }
    .btn-topbar-notif:hover { background: var(--dark); color: #fff; border-color: var(--dark); }
    .notif-dot {
      position: absolute;
      top: 5px; right: 5px;
      width: 8px; height: 8px;
      background: var(--accent-1);
      border-radius: 50%;
      border: 1.5px solid #fff;
    }
    .btn-topbar-action {
      background: var(--accent-4);
      color: #fff; border: none;
      border-radius: 10px;
      padding: 0.45rem 1rem;
      font-size: 0.8rem; font-weight: 500;
      font-family: 'DM Sans', sans-serif;
      cursor: pointer; transition: background 0.2s, transform 0.15s;
      display: flex; align-items: center; gap: 0.4rem;
      text-decoration: none;
    }
    .btn-topbar-action:hover { background: #6a22d0; transform: translateY(-1px); color: #fff; }

    /* ─── PAGE BODY ─── */
    .page-body { padding: 2rem; flex: 1; }

    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
      .sidebar.open { transform: translateX(0); }
      .main-content { padding-left: 0; }
    }
  </style>
  @yield('extra_css')
</head>
<body>
  <aside class="sidebar">
    <div class="sidebar-logo">
      <a href="{{ route('home') }}">Museo<span>.</span></a>
      <div class="admin-pill">Panel Admin</div>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-section-label">Principal</div>
      <a class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-grid-1x2"></i> Dashboard
      </a>
      <a class="nav-item {{ request()->routeIs('admin.obras.*') ? 'active' : '' }}" href="{{ route('admin.obras.index') }}">
        <i class="bi bi-palette"></i> Gestión de Obras
      </a>
      <a class="nav-item {{ request()->routeIs('admin.artistas.*') ? 'active' : '' }}" href="{{ route('admin.artistas.index') }}">
        <i class="bi bi-people"></i> Gestión de Artistas
      </a>
      <div class="nav-section-label">Ventas</div>
      <a class="nav-item {{ request()->routeIs('admin.facturas.index') || request()->routeIs('admin.facturas.show') ? 'active' : '' }}" href="{{ route('admin.facturas.index') }}">
        <i class="bi bi-file-earmark-text"></i> Gestión de Facturas
      </a>
      <a class="nav-item {{ request()->routeIs('admin.facturas.create') ? 'active' : '' }}" href="{{ route('admin.facturas.create') }}">
        <i class="bi bi-receipt"></i> Módulo de Cobro
      </a>
      <a class="nav-item {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}" href="{{ route('admin.reportes.ventas') }}">
        <i class="bi bi-bar-chart-line"></i> Reportes
      </a>
      <div class="nav-section-label">Sistema</div>
      <a class="nav-item {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}" href="{{ route('admin.usuarios.index') }}">
        <i class="bi bi-person-gear"></i> Gestión de Usuarios
      </a>
    </nav>
    <div class="sidebar-footer">
      <div class="admin-user-row">
        <div class="admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
        <div>
          <div class="admin-name" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</div>
          <div class="admin-role">{{ ucfirst(auth()->user()->role) }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="ms-auto">
            @csrf
            <button type="submit" class="btn-logout" title="Cerrar sesión">
              <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
      </div>
    </div>
  </aside>
  <div class="main-content">
    <div class="topbar">
      <div>
        <div class="topbar-title">@yield('topbar_title')</div>
        <div class="topbar-subtitle">@yield('topbar_subtitle')</div>
      </div>
      <div class="topbar-right">
        <span class="topbar-date" id="currentDate"></span>
        <div class="btn-topbar-notif">
          <i class="bi bi-bell"></i>
          <div class="notif-dot"></div>
        </div>
        @yield('topbar_actions')
      </div>
    </div>
    <div class="page-body">
        @yield('content')
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const now = new Date();
    const opts = { weekday:'long', year:'numeric', month:'long', day:'numeric' };
    const dateEl = document.getElementById('currentDate');
    if (dateEl) dateEl.textContent = now.toLocaleDateString('es-ES', opts);
  </script>
  @yield('extra_js')
</body>
</html>

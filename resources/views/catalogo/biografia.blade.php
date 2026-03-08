@extends('layouts.app')

@section('title', $artista->nombre . ' — Museo de Arte Contemporáneo')

@section('content')

<style>
  :root {
    --accent-1: #FF4D6D; --accent-2: #FFBE0B; --accent-3: #3A86FF;
    --accent-4: #8338EC; --dark: #0D0D0D; --mid: #1A1A2E; --light: #F8F5F0;
  }
  * { box-sizing: border-box; }

  /* NAVBAR */
  .navbar-mac { background: var(--dark); padding: 0.85rem 2rem; position: sticky; top: 0; z-index: 1000; border-bottom: 2px solid var(--accent-1); }
  .navbar-brand-custom { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 900; color: #fff; letter-spacing: -0.5px; text-decoration: none; }
  .navbar-brand-custom span { color: var(--accent-2); }
  .nav-link-custom { color: rgba(255,255,255,0.75); font-size: 0.875rem; font-weight: 500; text-decoration: none; margin-left: 1.5rem; transition: color 0.2s; }
  .nav-link-custom:hover { color: var(--accent-2); }
  .btn-nav-login { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,0.35); border-radius: 50px; padding: 0.4rem 1.2rem; font-size: 0.875rem; font-weight: 500; margin-left: 0.75rem; text-decoration: none; transition: border-color 0.2s, color 0.2s; }
  .btn-nav-login:hover { border-color: var(--accent-2); color: var(--accent-2); }

  /* BREADCRUMB */
  .breadcrumb-bar { background: var(--dark); padding: 0.6rem 2rem; border-bottom: 1px solid rgba(255,255,255,0.07); }
  .breadcrumb-bar a { color: rgba(255,255,255,0.45); font-size: 0.78rem; text-decoration: none; transition: color 0.2s; }
  .breadcrumb-bar a:hover { color: var(--accent-2); }
  .breadcrumb-bar span { color: rgba(255,255,255,0.25); margin: 0 0.4rem; font-size: 0.78rem; }
  .breadcrumb-bar .current { color: rgba(255,255,255,0.7); font-size: 0.78rem; }

  /* HERO */
  .artist-hero { background: var(--dark); position: relative; overflow: hidden; padding: 4rem 2rem; }
  .artist-hero::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 80% 50%, rgba(58,134,255,0.2) 0%, transparent 60%), radial-gradient(ellipse at 10% 70%, rgba(255,77,109,0.15) 0%, transparent 55%); }
  .artist-hero-inner { max-width: 1300px; margin: 0 auto; position: relative; z-index: 1; }
  .artist-photo-wrap { position: relative; display: inline-block; }
  .artist-photo { width: 200px; height: 200px; border-radius: 24px; object-fit: cover; border: 3px solid rgba(255,255,255,0.1); box-shadow: 0 20px 60px rgba(0,0,0,0.5); }
  .artist-photo-badge { position: absolute; bottom: -10px; right: -10px; background: var(--accent-2); color: #000; border-radius: 50%; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.3); }
  .artist-hero-info { padding-left: 2.5rem; }
  .artist-hero-label { font-size: 0.72rem; letter-spacing: 3px; text-transform: uppercase; color: var(--accent-3); margin-bottom: 0.5rem; }
  .artist-hero-name { font-family: 'Playfair Display', serif; font-size: clamp(2.5rem, 4vw, 3.5rem); font-weight: 900; color: #fff; line-height: 1.05; }
  .artist-hero-name em { font-style: italic; color: var(--accent-2); }
  .artist-meta-pills { display: flex; flex-wrap: wrap; gap: 0.6rem; margin-top: 1.25rem; }
  .meta-pill { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); border-radius: 50px; padding: 0.3rem 0.9rem; font-size: 0.78rem; color: rgba(255,255,255,0.75); display: flex; align-items: center; gap: 0.4rem; }
  .meta-pill i { color: var(--accent-2); }
  .genre-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem; }
  .genre-tag { border-radius: 50px; padding: 0.25rem 0.85rem; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #fff; background: var(--accent-4); }
  .artist-stats-row { display: flex; gap: 2rem; margin-top: 1.5rem; }
  .a-stat-val { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: #fff; }
  .a-stat-label { font-size: 0.72rem; color: rgba(255,255,255,0.45); text-transform: uppercase; letter-spacing: 1px; margin-top: 0.1rem; }

  /* MAIN */
  .bio-wrap { max-width: 1300px; margin: 3rem auto; padding: 0 2rem; }
  .bio-card { background: #fff; border-radius: 20px; padding: 2rem; box-shadow: 0 2px 20px rgba(0,0,0,0.06); height: 100%; }
  .bio-card-title { font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--accent-1); display: inline-block; }
  .bio-text { font-size: 0.9rem; line-height: 1.85; color: #555; }
  .bio-text p + p { margin-top: 1rem; }

  /* TIMELINE */
  .timeline { list-style: none; padding: 0; margin: 0; position: relative; }
  .timeline::before { content:''; position:absolute; left:15px; top:0; bottom:0; width:2px; background: linear-gradient(to bottom, var(--accent-1), var(--accent-4)); border-radius:2px; }
  .timeline li { padding: 0 0 1.5rem 2.75rem; position: relative; }
  .timeline li:last-child { padding-bottom: 0; }
  .timeline-dot { position:absolute; left:7px; top:4px; width:18px; height:18px; border-radius:50%; background: var(--accent-2); border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
  .timeline-year { font-size:0.72rem; font-weight:700; letter-spacing:2px; color: var(--accent-3); text-transform:uppercase; }
  .timeline-event { font-size:0.875rem; color: var(--dark); font-weight:500; margin-top:0.2rem; line-height:1.4; }
  .timeline-detail { font-size:0.78rem; color:#999; margin-top:0.15rem; }

  /* TABS */
  .tabs-nav { display: flex; gap: 0; border-bottom: 2px solid #e8e3dc; margin-bottom: 1.5rem; }
  .tab-btn { background: none; border: none; padding: 0.7rem 1.2rem; font-size: 0.875rem; font-weight: 500; color: #aaa; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: color 0.2s, border-color 0.2s; }
  .tab-btn.active { color: var(--dark); border-bottom-color: var(--accent-1); font-weight: 700; }
  .tab-content { display: none; }
  .tab-content.active { display: block; }

  /* OBRA MINI CARD */
  .obra-mini { background: #fff; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); transition: transform 0.3s, box-shadow 0.3s; height: 100%; display: flex; flex-direction: column; }
  .obra-mini:hover { transform: translateY(-4px); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
  .obra-mini img { width: 100%; aspect-ratio: 4/3; object-fit: cover; }
  .obra-mini-body { padding: 0.85rem; flex: 1; display: flex; flex-direction: column; }
  .obra-mini-title { font-family: 'Playfair Display', serif; font-size: 0.95rem; font-weight: 700; }
  .obra-mini-meta { font-size: 0.75rem; color: #999; margin-top: 0.2rem; }
  .obra-mini-footer { display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 0.75rem; }
  .obra-mini-price { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; }
  .btn-mini-ver { background: var(--dark); color: #fff; border: none; border-radius: 50px; padding: 0.3rem 0.85rem; font-size: 0.72rem; font-weight: 600; text-decoration: none; transition: background 0.2s; }
  .btn-mini-ver:hover { background: var(--accent-1); color: #fff; }

  /* AWARDS */
  .award-item { display: flex; align-items: flex-start; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid #f0ece6; }
  .award-item:last-child { border-bottom: none; }
  .award-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
  .award-title { font-size: 0.875rem; font-weight: 600; color: var(--dark); }
  .award-year { font-size: 0.75rem; color: #aaa; margin-top: 0.15rem; }

  /* FOOTER */
  .footer-mac { background: var(--dark); color: rgba(255,255,255,0.5); text-align: center; padding: 2rem; font-size: 0.82rem; border-top: 2px solid var(--accent-4); margin-top: 4rem; }
  .footer-mac strong { color: var(--accent-2); }

  @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
  .fade-up { animation: fadeUp 0.5s ease both; }
  .fade-up-1 { animation-delay: 0.1s; } .fade-up-2 { animation-delay: 0.2s; }
  .fade-up-3 { animation-delay: 0.3s; } .fade-up-4 { animation-delay: 0.4s; }
</style>

{{-- ====== NAVBAR ====== --}}
<nav class="navbar-mac d-flex justify-content-between align-items-center">
  <a href="{{ route('home') }}" class="navbar-brand-custom">MAC <span>·</span> Arte</a>
  <div class="d-flex align-items-center flex-wrap gap-1">
    <a href="{{ route('home') }}" class="nav-link-custom">Catálogo</a>
    <a href="#" class="nav-link-custom">Artistas</a>
    <a href="#" class="nav-link-custom">Géneros</a>
    @auth
      <a href="{{ route('dashboard') }}" class="btn-nav-login">Mi cuenta</a>
    @else
      <a href="{{ route('login') }}" class="btn-nav-login">Iniciar sesión</a>
    @endauth
  </div>
</nav>

{{-- ====== BREADCRUMB ====== --}}
<div class="breadcrumb-bar d-flex align-items-center">
  <a href="{{ route('home') }}">Catálogo</a>
  <span>›</span>
  <a href="#">Artistas</a>
  <span>›</span>
  <span class="current">{{ $artista->nombre }}</span>
</div>

{{-- ====== HERO ====== --}}
<div class="artist-hero">
  <div class="artist-hero-inner">
    <div class="row align-items-center">

      {{-- Foto --}}
      <div class="col-auto fade-up fade-up-1">
        <div class="artist-photo-wrap">
          <img
            src="{{ $artista->foto_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($artista->nombre) . '&size=200&background=3A86FF&color=fff' }}"
            class="artist-photo"
            alt="{{ $artista->nombre }}" />
          <div class="artist-photo-badge"><i class="bi bi-brush"></i></div>
        </div>
      </div>

      {{-- Info --}}
      <div class="col fade-up fade-up-2">
        <div class="artist-hero-info">
          <div class="artist-hero-label">Artista · Museo de Arte Contemporáneo</div>
          <h1 class="artist-hero-name">{{ $artista->nombre }}</h1>

          <div class="artist-meta-pills">
            @if($artista->nacionalidad)
              <span class="meta-pill"><i class="bi bi-geo-alt-fill"></i>{{ $artista->nacionalidad }}</span>
            @endif
            @if($artista->fecha_nacimiento)
              <span class="meta-pill"><i class="bi bi-calendar3"></i>{{ \Carbon\Carbon::parse($artista->fecha_nacimiento)->format('d/m/Y') }}</span>
            @endif
            <span class="meta-pill"><i class="bi bi-palette"></i>{{ $obras->count() }} obras en el museo</span>
            <span class="meta-pill"><i class="bi bi-bag-check"></i>{{ $obras->where('estado','Vendida')->count() }} obras vendidas</span>
          </div>

          {{-- Géneros del artista --}}
          <div class="genre-tags mt-2">
            @foreach($obras->pluck('genero.nombre')->unique()->filter() as $genero)
              <span class="genre-tag">{{ $genero }}</span>
            @endforeach
          </div>

          {{-- Stats --}}
          <div class="artist-stats-row">
            <div>
              <div class="a-stat-val">{{ $obras->count() }}</div>
              <div class="a-stat-label">Obras totales</div>
            </div>
            <div>
              <div class="a-stat-val">{{ $obras->where('estado','Disponible')->count() }}</div>
              <div class="a-stat-label">Disponibles</div>
            </div>
            <div>
              <div class="a-stat-val">{{ $obras->where('estado','Vendida')->count() }}</div>
              <div class="a-stat-label">Vendidas</div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- ====== CONTENIDO PRINCIPAL ====== --}}
<div class="bio-wrap">
  <div class="row g-4">

    {{-- COLUMNA IZQUIERDA: Biografía + Premios --}}
    <div class="col-lg-4">

      {{-- Biografía --}}
      <div class="bio-card fade-up fade-up-1 mb-4">
        <div class="bio-card-title">Biografía</div>
        <div class="bio-text">
          @if($artista->biografia)
            {!! nl2br(e($artista->biografia)) !!}
          @else
            <p>Información biográfica no disponible.</p>
          @endif
        </div>
      </div>

      {{-- Premios / Reconocimientos --}}
      <div class="bio-card fade-up fade-up-2">
        <div class="bio-card-title">Reconocimientos</div>
        <div class="award-item">
          <div class="award-icon" style="background:rgba(255,190,11,0.15);">
            <i class="bi bi-trophy-fill" style="color:var(--accent-2)"></i>
          </div>
          <div>
            <div class="award-title">Premio Nacional de Artes Plásticas</div>
            <div class="award-year">Ministerio de Cultura · 2021</div>
          </div>
        </div>
        <div class="award-item">
          <div class="award-icon" style="background:rgba(58,134,255,0.12);">
            <i class="bi bi-star-fill" style="color:var(--accent-3)"></i>
          </div>
          <div>
            <div class="award-title">Beca de Creación Artística</div>
            <div class="award-year">Fundación Arte Vivo · 2018</div>
          </div>
        </div>
        <div class="award-item">
          <div class="award-icon" style="background:rgba(131,56,236,0.12);">
            <i class="bi bi-award-fill" style="color:var(--accent-4)"></i>
          </div>
          <div>
            <div class="award-title">Artista Destacado — Bienal de Arte</div>
            <div class="award-year">Bienal Internacional · 2016</div>
          </div>
        </div>
      </div>

    </div>

    {{-- COLUMNA DERECHA: Timeline + Obras --}}
    <div class="col-lg-8">

      {{-- Timeline --}}
      <div class="bio-card fade-up fade-up-2 mb-4">
        <div class="bio-card-title">Trayectoria</div>
        <ul class="timeline">
          <li>
            <div class="timeline-dot"></div>
            <div class="timeline-year">Formación</div>
            <div class="timeline-event">Estudios en Bellas Artes</div>
            <div class="timeline-detail">Inicio de su carrera artística formal</div>
          </li>
          <li>
            <div class="timeline-dot"></div>
            <div class="timeline-year">Primera exposición</div>
            <div class="timeline-event">Debut en galería local</div>
            <div class="timeline-detail">Reconocimiento por la crítica especializada</div>
          </li>
          <li>
            <div class="timeline-dot"></div>
            <div class="timeline-year">Museo</div>
            <div class="timeline-event">Incorporación al Museo de Arte Contemporáneo</div>
            <div class="timeline-detail">Artista residente con exhibición permanente</div>
          </li>
          <li>
            <div class="timeline-dot"></div>
            <div class="timeline-year">Actualidad</div>
            <div class="timeline-event">{{ $obras->count() }} obras en el catálogo del museo</div>
            <div class="timeline-detail">{{ $obras->where('estado','Disponible')->count() }} disponibles para adquisición</div>
          </li>
        </ul>
      </div>

      {{-- TABS de obras --}}
      <div class="bio-card fade-up fade-up-3">
        <div class="bio-card-title">Obras en el Museo</div>

        <div class="tabs-nav">
          <button class="tab-btn active" onclick="showTab('disponibles', this)">
            Disponibles ({{ $obras->where('estado','Disponible')->count() }})
          </button>
          <button class="tab-btn" onclick="showTab('reservadas', this)">
            Reservadas ({{ $obras->where('estado','Reservada')->count() }})
          </button>
          <button class="tab-btn" onclick="showTab('vendidas', this)">
            Vendidas ({{ $obras->where('estado','Vendida')->count() }})
          </button>
        </div>

        {{-- TAB: Disponibles --}}
        <div class="tab-content active" id="tab-disponibles">
          <div class="row g-3">
            @forelse($obras->where('estado','Disponible') as $obra)
              <div class="col-6 col-xl-4">
                <div class="obra-mini">
                  <img
                    src="{{ $obra->imagen_url ?? 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?w=400&q=75' }}"
                    alt="{{ $obra->titulo }}" />
                  <div class="obra-mini-body">
                    <div class="obra-mini-title">{{ $obra->titulo }}</div>
                    <div class="obra-mini-meta">
                      {{ $obra->genero->nombre ?? '' }}
                      {{ $obra->anio ? '· ' . $obra->anio : '' }}
                      {{ $obra->dimensiones ? '· ' . $obra->dimensiones : '' }}
                    </div>
                    <div class="obra-mini-footer">
                      <div class="obra-mini-price">${{ number_format($obra->precio_venta, 0, ',', '.') }}</div>
                      <a href="{{ route('obra.detalle', $obra->id) }}" class="btn-mini-ver">Ver obra</a>
                    </div>
                  </div>
                </div>
              </div>
            @empty
              <div class="col-12">
                <p style="color:#aaa; font-size:0.875rem; text-align:center; padding:2rem 0;">
                  No hay obras disponibles actualmente.
                </p>
              </div>
            @endforelse
          </div>
        </div>

        {{-- TAB: Reservadas --}}
        <div class="tab-content" id="tab-reservadas">
          <div class="row g-3">
            @forelse($obras->where('estado','Reservada') as $obra)
              <div class="col-6 col-xl-4">
                <div class="obra-mini">
                  <img
                    src="{{ $obra->imagen_url ?? 'https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?w=400&q=75' }}"
                    alt="{{ $obra->titulo }}" />
                  <div class="obra-mini-body">
                    <div class="obra-mini-title">{{ $obra->titulo }}</div>
                    <div class="obra-mini-meta">{{ $obra->genero->nombre ?? '' }} {{ $obra->anio ? '· '.$obra->anio : '' }}</div>
                    <div class="obra-mini-footer">
                      <div class="obra-mini-price">${{ number_format($obra->precio_venta, 0, ',', '.') }}</div>
                      <span style="font-size:0.72rem; color:var(--accent-2); font-weight:600;">● Reservada</span>
                    </div>
                  </div>
                </div>
              </div>
            @empty
              <div class="col-12">
                <p style="color:#aaa; font-size:0.875rem; text-align:center; padding:2rem 0;">
                  No hay obras reservadas actualmente.
                </p>
              </div>
            @endforelse
          </div>
        </div>

        {{-- TAB: Vendidas --}}
        <div class="tab-content" id="tab-vendidas">
          @if($obras->where('estado','Vendida')->count() > 0)
            <div class="text-center py-4" style="color:#bbb;">
              <i class="bi bi-check-circle" style="font-size:2.5rem; color:#4ade80;"></i>
              <p class="mt-2" style="font-size:0.875rem;">
                <strong>{{ $obras->where('estado','Vendida')->count() }}</strong> obras de este artista han sido vendidas con éxito.
              </p>
            </div>
          @else
            <p style="color:#aaa; font-size:0.875rem; text-align:center; padding:2rem 0;">
              Aún no hay obras vendidas.
            </p>
          @endif
        </div>

      </div>
    </div>

  </div>
</div>

{{-- ====== FOOTER ====== --}}
<footer class="footer-mac">
  <p>© {{ date('Y') }} <strong>Museo de Arte Contemporáneo</strong> · Todos los derechos reservados</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function showTab(id, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-' + id).classList.add('active');
  }
</script>

@endsection
@extends('layouts.app')

@section('title', 'Museo de Arte Contemporáneo — Catálogo')

@section('content')

<style>
  :root {
    --accent-1: #FF4D6D; --accent-2: #FFBE0B; --accent-3: #3A86FF;
    --accent-4: #8338EC; --dark: #0D0D0D; --mid: #1A1A2E; --light: #F8F5F0;
  }
  * { box-sizing: border-box; }
  body { background: var(--light); }

  /* NAVBAR */
  .navbar-mac { background: var(--dark); padding: 0.85rem 2rem; position: sticky; top: 0; z-index: 1000; border-bottom: 2px solid var(--accent-1); }
  .navbar-brand-custom { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 900; color: #fff; letter-spacing: -0.5px; text-decoration: none; }
  .navbar-brand-custom span { color: var(--accent-2); }
  .nav-link-custom { color: rgba(255,255,255,0.75); font-size: 0.875rem; font-weight: 500; text-decoration: none; margin-left: 1.5rem; transition: color 0.2s; }
  .nav-link-custom:hover { color: var(--accent-2); }
  .btn-nav-register { background: var(--accent-1); color: #fff; border: none; border-radius: 50px; padding: 0.4rem 1.2rem; font-size: 0.875rem; font-weight: 500; margin-left: 1.5rem; text-decoration: none; transition: background 0.2s; }
  .btn-nav-register:hover { background: #e0003c; color: #fff; }
  .btn-nav-login { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,0.35); border-radius: 50px; padding: 0.4rem 1.2rem; font-size: 0.875rem; font-weight: 500; margin-left: 0.75rem; text-decoration: none; transition: border-color 0.2s, color 0.2s; }
  .btn-nav-login:hover { border-color: var(--accent-2); color: var(--accent-2); }
  .btn-nav-user { background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2); border-radius: 50px; padding: 0.4rem 1.2rem; font-size: 0.875rem; margin-left: 0.75rem; text-decoration: none; transition: all 0.2s; }
  .btn-nav-user:hover { background: rgba(255,255,255,0.2); color: #fff; }

  /* HERO */
  .hero { background: var(--dark); position: relative; overflow: hidden; padding: 5rem 2rem 4rem; }
  .hero::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 70% 50%, rgba(131,56,236,0.25) 0%, transparent 65%), radial-gradient(ellipse at 20% 80%, rgba(255,77,109,0.2) 0%, transparent 60%); }
  .hero-inner { max-width: 1400px; margin: 0 auto; position: relative; z-index: 1; }
  .hero-label { font-size: 0.75rem; font-weight: 500; letter-spacing: 4px; text-transform: uppercase; color: var(--accent-2); margin-bottom: 1rem; }
  .hero-title { font-family: 'Playfair Display', serif; font-size: clamp(2.8rem, 6vw, 5rem); font-weight: 900; color: #fff; line-height: 1.05; }
  .hero-title em { font-style: italic; color: var(--accent-2); }
  .hero-sub { color: rgba(255,255,255,0.6); font-size: 1rem; margin-top: 1rem; max-width: 500px; line-height: 1.6; }
  .hero-stats { display: flex; gap: 2.5rem; margin-top: 2.5rem; }
  .hero-stat-val { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 700; color: #fff; }
  .hero-stat-label { font-size: 0.75rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1px; }
  .hero-badge-row { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 2rem; }
  .hero-badge { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: rgba(255,255,255,0.75); border-radius: 50px; padding: 0.3rem 0.9rem; font-size: 0.78rem; cursor: pointer; transition: all 0.2s; border: none; font-family: 'DM Sans', sans-serif; }
  .hero-badge:hover, .hero-badge.active { background: var(--accent-1); color: #fff; }

  /* SEARCH */
  .search-section { background: var(--dark); padding: 0 2rem 2.5rem; }
  .search-inner { max-width: 1400px; margin: 0 auto; }
  .search-bar { background: rgba(255,255,255,0.06); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 16px; padding: 1rem 1.5rem; display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
  .search-input { flex: 1; min-width: 200px; background: transparent; border: none; outline: none; color: #fff; font-size: 0.95rem; font-family: 'DM Sans', sans-serif; }
  .search-input::placeholder { color: rgba(255,255,255,0.35); }
  .search-divider { width: 1px; height: 24px; background: rgba(255,255,255,0.15); }
  .search-select { background: transparent; border: none; outline: none; color: rgba(255,255,255,0.7); font-size: 0.875rem; font-family: 'DM Sans', sans-serif; cursor: pointer; }
  .search-select option { background: var(--mid); color: #fff; }
  .btn-search { background: var(--accent-3); color: #fff; border: none; border-radius: 10px; padding: 0.55rem 1.4rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: background 0.2s; font-family: 'DM Sans', sans-serif; }
  .btn-search:hover { background: #1f6fd4; }

  /* CATALOG */
  .catalog-section { padding: 3rem 2rem; max-width: 1400px; margin: 0 auto; }
  .section-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
  .section-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: var(--dark); }
  .section-count { font-size: 0.85rem; color: #888; }
  .sort-bar { display: flex; gap: 0.5rem; flex-wrap: wrap; }
  .sort-btn { background: #fff; border: 1.5px solid #e0e0e0; border-radius: 50px; padding: 0.35rem 1rem; font-size: 0.8rem; color: #555; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all 0.2s; }
  .sort-btn.active, .sort-btn:hover { background: var(--dark); border-color: var(--dark); color: #fff; }

  /* ART CARDS */
  .art-card { background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 2px 20px rgba(0,0,0,0.07); transition: transform 0.3s cubic-bezier(.22,.68,0,1.2), box-shadow 0.3s; height: 100%; display: flex; flex-direction: column; }
  .art-card:hover { transform: translateY(-6px); box-shadow: 0 12px 40px rgba(0,0,0,0.14); }
  .art-card-img-wrap { position: relative; overflow: hidden; aspect-ratio: 4/3; }
  .art-card-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
  .art-card:hover .art-card-img-wrap img { transform: scale(1.06); }
  .art-genre-badge { position: absolute; top: 12px; left: 12px; border-radius: 50px; padding: 0.25rem 0.8rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #fff; background: var(--accent-1); }
  .art-status { position: absolute; top: 12px; right: 12px; background: rgba(0,0,0,0.55); backdrop-filter: blur(6px); color: #fff; border-radius: 50px; padding: 0.25rem 0.75rem; font-size: 0.7rem; font-weight: 500; }
  .art-status.disponible { color: #4ade80; }
  .art-status.reservada  { color: var(--accent-2); }
  .art-status.vendida    { color: #ff6b6b; }
  .art-card-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; gap: 0.4rem; }
  .art-title { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; color: var(--dark); line-height: 1.3; }
  .art-artist { font-size: 0.82rem; color: var(--accent-3); font-weight: 500; text-decoration: none; }
  .art-artist:hover { text-decoration: underline; color: var(--accent-4); }
  .art-meta { font-size: 0.78rem; color: #999; }
  .art-card-footer { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem; border-top: 1px solid #f0f0f0; margin-top: auto; }
  .art-price { font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700; color: var(--dark); }
  .btn-ver-detalle { background: transparent; border: 1.5px solid var(--dark); color: var(--dark); border-radius: 50px; padding: 0.45rem 1.1rem; font-size: 0.8rem; font-weight: 500; text-decoration: none; transition: all 0.2s; }
  .btn-ver-detalle:hover { background: var(--dark); color: #fff; }
  .btn-comprar { background: var(--accent-1); color: #fff; border: none; border-radius: 50px; padding: 0.45rem 1.1rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: background 0.2s; font-family: 'DM Sans', sans-serif; }
  .btn-comprar:hover { background: #d4003a; color: #fff; }

  /* EMPTY STATE */
  .empty-state { text-align: center; padding: 4rem 2rem; color: #ccc; }
  .empty-state i { font-size: 3rem; margin-bottom: 1rem; display: block; }

  /* FOOTER */
  .footer-mac { background: var(--dark); color: rgba(255,255,255,0.5); text-align: center; padding: 2rem; font-size: 0.82rem; border-top: 2px solid var(--accent-4); margin-top: 2rem; }
  .footer-mac strong { color: var(--accent-2); }

  /* ANIMATIONS */
  @keyframes fadeUp { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }
  .fade-up { animation: fadeUp 0.6s ease both; }
  .fade-up-1 { animation-delay:0.1s; } .fade-up-2 { animation-delay:0.2s; }
  .fade-up-3 { animation-delay:0.3s; } .fade-up-4 { animation-delay:0.4s; }
  .fade-up-5 { animation-delay:0.5s; } .fade-up-6 { animation-delay:0.6s; }

  @media(max-width:768px) {
    .hero-stats { gap: 1.5rem; }
    .search-divider { display: none; }
  }
</style>

{{-- ====== NAVBAR ====== --}}
<nav class="navbar-mac d-flex justify-content-between align-items-center">
  <a href="{{ route('home') }}" class="navbar-brand-custom">MAC <span>·</span> Arte</a>
  <div class="d-flex align-items-center flex-wrap gap-1">
    <a href="{{ route('home') }}" class="nav-link-custom">Catálogo</a>
    <a href="{{ route('catalogo.artistas') }}" class="nav-link-custom">Artistas</a>
    <a href="{{ route('catalogo.generos') }}" class="nav-link-custom">Géneros</a>
    @auth
      <a href="{{ route('dashboard') }}" class="btn-nav-user">
        <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
      </a>
      <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" class="btn-nav-login" style="cursor:pointer">Salir</button>
      </form>
    @else
      <a href="{{ route('login') }}" class="btn-nav-login">Iniciar sesión</a>
      <a href="{{ route('register') }}" class="btn-nav-register">Registrarse</a>
    @endauth
  </div>
</nav>

{{-- ====== HERO ====== --}}
<section class="hero">
  <div class="hero-inner">
    <p class="hero-label fade-up fade-up-1">Colección Permanente</p>
    <h1 class="hero-title fade-up fade-up-2">
      Arte que <em>transforma</em><br> espacios
    </h1>
    <p class="hero-sub fade-up fade-up-3">
      Descubre obras únicas de artistas contemporáneos. Cada pieza es original, certificada y lista para tu hogar o colección.
    </p>

    {{-- Stats reales de la BD --}}
    <div class="hero-stats fade-up fade-up-4">
      <div>
        <div class="hero-stat-val">{{ $obras->count() }}</div>
        <div class="hero-stat-label">Obras disponibles</div>
      </div>
      <div>
        <div class="hero-stat-val">{{ $artistas ?? '—' }}</div>
        <div class="hero-stat-label">Artistas activos</div>
      </div>
      <div>
        <div class="hero-stat-val">{{ $generos ?? '—' }}</div>
        <div class="hero-stat-label">Géneros</div>
      </div>
    </div>

    {{-- Filtros rápidos por género --}}
    <div class="hero-badge-row fade-up fade-up-5">
      <button class="hero-badge active" onclick="filterGenre(this, '')">Todos</button>
      @foreach($generosLista ?? [] as $g)
        <button class="hero-badge" onclick="filterGenre(this, '{{ $g->nombre }}')">{{ $g->nombre }}</button>
      @endforeach
    </div>
  </div>
</section>

{{-- ====== SEARCH BAR ====== --}}
<div class="search-section">
  <div class="search-inner">
    <div class="search-bar">
      <i class="bi bi-search" style="color:rgba(255,255,255,0.4)"></i>
      <input type="text" class="search-input" id="searchInput"
             placeholder="Buscar por título o artista..."
             oninput="filterCards()" />
      <div class="search-divider"></div>
      <select class="search-select" id="genreFilter" onchange="filterCards()">
        <option value="">Todos los géneros</option>
        @foreach($generosLista ?? [] as $g)
          <option value="{{ $g->nombre }}">{{ $g->nombre }}</option>
        @endforeach
      </select>
      <div class="search-divider"></div>
      <select class="search-select" id="artistFilter" onchange="filterCards()">
        <option value="">Todos los artistas</option>
        @foreach($artistasLista ?? [] as $a)
          <option value="{{ $a->nombre }}">{{ $a->nombre }}</option>
        @endforeach
      </select>
      <button class="btn-search" onclick="filterCards()">
        <i class="bi bi-search me-1"></i> Buscar
      </button>
    </div>
  </div>
</div>

{{-- ====== CATÁLOGO ====== --}}
<section class="catalog-section">

  <div class="section-header">
    <div>
      <h2 class="section-title">Obras en exhibición</h2>
      <p class="section-count" id="countLabel">Mostrando {{ $obras->count() }} obras</p>
    </div>
    <div class="sort-bar">
      <button class="sort-btn active" onclick="sortCards('default', this)">Destacadas</button>
      <button class="sort-btn" onclick="sortCards('asc', this)">Precio: menor</button>
      <button class="sort-btn" onclick="sortCards('desc', this)">Precio: mayor</button>
    </div>
  </div>

  {{-- SUCCESS MESSAGE --}}
  @if(session('success'))
    <div style="background:rgba(6,214,160,0.1);border:1px solid rgba(6,214,160,0.3);border-radius:12px;padding:0.85rem 1.2rem;margin-bottom:1.5rem;color:#019975;font-size:0.875rem;display:flex;align-items:center;gap:0.5rem;">
      <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
  @endif

  {{-- GRID DE OBRAS --}}
  <div class="row g-4" id="cardsContainer">
    @forelse($obras as $obra)
      <div class="col-12 col-sm-6 col-lg-4 col-xl-3 art-card-col fade-up fade-up-{{ ($loop->index % 6) + 1 }}"
           data-genre="{{ $obra->genero->nombre ?? '' }}"
           data-artist="{{ $obra->artista->nombre ?? '' }}"
           data-price="{{ $obra->precio_venta ?? 0 }}"
           data-title="{{ $obra->titulo }}">
        <div class="art-card">

          {{-- IMAGEN --}}
          <div class="art-card-img-wrap">
            <img src="{{ asset('storage/' . $obra->image_url) }}"
                 alt="{{ $obra->titulo }}" loading="lazy" />
            <span class="art-genre-badge">{{ $obra->genero->nombre ?? 'Arte' }}</span>
            <span class="art-status {{ strtolower($obra->estado ?? 'disponible') }}">
              <i class="bi bi-circle-fill me-1" style="font-size:0.5rem"></i>
              {{ $obra->estado ?? 'Disponible' }}
            </span>
          </div> 

          {{-- BODY --}}
          <div class="art-card-body">
            <div class="art-title">{{ $obra->titulo }}</div>
            <a href="{{ route('catalogo.biografia', $obra->artista->id ?? 0) }}" class="art-artist">
              {{ $obra->artista->nombre ?? 'Artista desconocido' }}
            </a>
            <div class="art-meta">{{ $obra->material ?? '' }} {{ $obra->anio ? '· ' . $obra->anio : '' }}</div>
            <div class="art-meta">{{ $obra->dimensiones ?? '' }}</div>
          </div>

          {{-- FOOTER --}}
          <div class="art-card-footer">
            <div class="art-price">${{ number_format($obra->precio_venta ?? 0, 0, ',', '.') }}</div>
            <div class="d-flex gap-2">
              <a href="{{ route('obra.detalle', $obra->id) }}" class="btn-ver-detalle">Ver</a>
              @if(($obra->estado ?? 'Disponible') === 'Disponible')
                <a href="{{ route('catalogo.validacion', $obra->id) }}" class="btn-comprar">Comprar</a>
              @else
                <button class="btn-comprar" disabled style="opacity:0.4;cursor:not-allowed">
                  {{ $obra->estado }}
                </button>
              @endif
            </div>
          </div>

        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="empty-state">
          <i class="bi bi-palette"></i>
          <p>No hay obras en el catálogo todavía.</p>
        </div>
      </div>
    @endforelse
  </div>

  {{-- PAGINACIÓN --}}
@if(method_exists($obras, 'hasPages') && $obras->hasPages())
  <div class="d-flex justify-content-center mt-4">
    {{ $obras->links() }}
  </div>
@endif

  {{-- EMPTY STATE (filtros) --}}
  <div id="emptyState" class="empty-state d-none">
    <i class="bi bi-search"></i>
    <p>No se encontraron obras con esos criterios.</p>
    <button class="btn-search mt-3" onclick="resetFilters()">Limpiar filtros</button>
  </div>

</section>

{{-- ====== FOOTER ====== --}}
<footer class="footer-mac">
  <p>© {{ date('Y') }} <strong>Museo de Arte Contemporáneo</strong> · Todos los derechos reservados</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function filterGenre(el, genre) {
    document.querySelectorAll('.hero-badge').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('genreFilter').value = genre;
    filterCards();
  }

  function filterCards() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const genre  = document.getElementById('genreFilter').value;
    const artist = document.getElementById('artistFilter').value;
    const cards  = document.querySelectorAll('.art-card-col');
    let visible  = 0;
    cards.forEach(card => {
      const match =
        (!search || card.dataset.title.toLowerCase().includes(search) || card.dataset.artist.toLowerCase().includes(search)) &&
        (!genre  || card.dataset.genre  === genre) &&
        (!artist || card.dataset.artist === artist);
      card.style.display = match ? '' : 'none';
      if (match) visible++;
    });
    document.getElementById('countLabel').textContent = `Mostrando ${visible} obra${visible !== 1 ? 's' : ''}`;
    document.getElementById('emptyState').classList.toggle('d-none', visible > 0);
  }

  function sortCards(order, btn) {
    document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const container = document.getElementById('cardsContainer');
    const cards = [...container.querySelectorAll('.art-card-col')];
    cards.sort((a, b) =>
      order === 'desc'
        ? parseInt(b.dataset.price) - parseInt(a.dataset.price)
        : order === 'asc'
          ? parseInt(a.dataset.price) - parseInt(b.dataset.price)
          : 0
    );
    cards.forEach(c => container.appendChild(c));
  }

  function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('genreFilter').value = '';
    document.getElementById('artistFilter').value = '';
    document.querySelectorAll('.hero-badge').forEach((b, i) => b.classList.toggle('active', i === 0));
    filterCards();
  }
</script>

@endsection
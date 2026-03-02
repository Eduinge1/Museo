@extends('layouts.app')

@section('title', $obra->titulo . ' — Museo de Arte Contemporáneo')

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

  /* MAIN LAYOUT */
  .detail-wrap { max-width: 1300px; margin: 3rem auto; padding: 0 2rem; }

  /* IMAGE SIDE */
  .img-main-wrap { position: relative; border-radius: 24px; overflow: hidden; background: #e8e3dc; aspect-ratio: 4/3; }
  .img-main-wrap img { width: 100%; height: 100%; object-fit: cover; }
  .img-genre-badge { position: absolute; top: 16px; left: 16px; background: var(--accent-1); color: #fff; border-radius: 50px; padding: 0.3rem 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
  .img-status-badge { position: absolute; top: 16px; right: 16px; background: rgba(0,0,0,0.6); backdrop-filter: blur(8px); color: #4ade80; border-radius: 50px; padding: 0.3rem 0.9rem; font-size: 0.75rem; font-weight: 500; }
  .img-zoom-btn { position: absolute; bottom: 16px; right: 16px; background: rgba(0,0,0,0.5); backdrop-filter: blur(6px); color: #fff; border: none; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s; font-size: 1rem; }
  .img-zoom-btn:hover { background: rgba(0,0,0,0.8); }
  .thumbs-row { display: flex; gap: 0.75rem; margin-top: 1rem; }
  .thumb { width: 80px; height: 60px; border-radius: 10px; overflow: hidden; cursor: pointer; border: 2px solid transparent; transition: border-color 0.2s; flex-shrink: 0; }
  .thumb.active { border-color: var(--accent-1); }
  .thumb img { width: 100%; height: 100%; object-fit: cover; }

  /* INFO SIDE */
  .info-side { padding-left: 1rem; }
  .obra-label { font-size: 0.72rem; font-weight: 500; letter-spacing: 3px; text-transform: uppercase; color: var(--accent-3); margin-bottom: 0.5rem; }
  .obra-title { font-family: 'Playfair Display', serif; font-size: clamp(2rem, 3.5vw, 2.8rem); font-weight: 900; line-height: 1.1; color: var(--dark); }
  .obra-artist-row { display: flex; align-items: center; gap: 0.75rem; margin-top: 1rem; }
  .artist-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent-3); }
  .artist-name-link { font-size: 0.9rem; font-weight: 600; color: var(--accent-3); text-decoration: none; }
  .artist-name-link:hover { text-decoration: underline; color: var(--accent-4); }
  .artist-nationality { font-size: 0.75rem; color: #999; }

  /* PRICE BLOCK */
  .price-block { background: var(--dark); border-radius: 18px; padding: 1.5rem; margin-top: 1.75rem; }
  .price-label { font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.4); }
  .price-value { font-family: 'Playfair Display', serif; font-size: 2.4rem; font-weight: 900; color: #fff; line-height: 1; margin-top: 0.25rem; }
  .price-note { font-size: 0.75rem; color: rgba(255,255,255,0.4); margin-top: 0.4rem; }
  .price-note strong { color: var(--accent-2); }
  .btn-comprar-main { width: 100%; background: var(--accent-1); color: #fff; border: none; border-radius: 14px; padding: 1rem; font-size: 1rem; font-weight: 700; margin-top: 1rem; cursor: pointer; transition: background 0.2s, transform 0.15s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
  .btn-comprar-main:hover { background: #d4003a; transform: scale(1.02); }
  .btn-wishlist { width: 100%; background: transparent; color: rgba(255,255,255,0.6); border: 1px solid rgba(255,255,255,0.15); border-radius: 14px; padding: 0.75rem; font-size: 0.875rem; margin-top: 0.75rem; cursor: pointer; transition: border-color 0.2s, color 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
  .btn-wishlist:hover { border-color: var(--accent-2); color: var(--accent-2); }

  /* TABS */
  .tabs-nav { display: flex; gap: 0; margin-top: 2rem; border-bottom: 2px solid #e8e3dc; }
  .tab-btn { background: none; border: none; padding: 0.75rem 1.25rem; font-size: 0.875rem; font-weight: 500; color: #aaa; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: color 0.2s, border-color 0.2s; }
  .tab-btn.active { color: var(--dark); border-bottom-color: var(--accent-1); font-weight: 700; }
  .tab-content { display: none; padding-top: 1.5rem; }
  .tab-content.active { display: block; }

  /* SPECS TABLE */
  .specs-table { width: 100%; border-collapse: collapse; }
  .specs-table tr { border-bottom: 1px solid #f0ece6; }
  .specs-table tr:last-child { border-bottom: none; }
  .specs-table td { padding: 0.75rem 0; font-size: 0.875rem; }
  .specs-table td:first-child { color: #999; width: 45%; }
  .specs-table td:last-child { color: var(--dark); font-weight: 500; }

  /* STATUS TIMELINE */
  .status-row { display: flex; align-items: center; gap: 0; margin-top: 1.5rem; }
  .status-step { display: flex; flex-direction: column; align-items: center; flex: 1; }
  .status-circle { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; }
  .status-circle.done { background: #4ade80; color: #000; }
  .status-circle.current { background: var(--accent-2); color: #000; }
  .status-circle.pending { background: #e8e3dc; color: #aaa; }
  .status-line { height: 2px; flex: 1; background: #e8e3dc; margin: 0 -1px; margin-top: -17px; z-index: 0; }
  .status-line.done { background: #4ade80; }
  .status-label { font-size: 0.68rem; color: #999; margin-top: 0.4rem; text-align: center; }
  .status-label.active-label { color: var(--dark); font-weight: 600; }

  /* RELATED */
  .related-section { max-width: 1300px; margin: 3rem auto 4rem; padding: 0 2rem; }
  .section-title-rel { font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 700; margin-bottom: 1.5rem; }
  .rel-card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 16px rgba(0,0,0,0.06); transition: transform 0.3s, box-shadow 0.3s; }
  .rel-card:hover { transform: translateY(-4px); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
  .rel-card img { width: 100%; aspect-ratio: 4/3; object-fit: cover; }
  .rel-card-body { padding: 1rem; }
  .rel-title { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; }
  .rel-artist { font-size: 0.78rem; color: var(--accent-3); margin-top: 0.25rem; text-decoration: none; display: block; }
  .rel-price { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; margin-top: 0.5rem; }

  /* MODAL */
  .modal-content { border-radius: 20px; border: none; }
  .modal-header { border-bottom: 1px solid #f0ece6; padding: 1.5rem; }
  .modal-title { font-family: 'Playfair Display', serif; font-weight: 700; }
  .code-input { text-align: center; font-size: 1.8rem; font-family: 'Playfair Display', serif; font-weight: 700; letter-spacing: 0.5rem; border: 2px solid #e0e0e0; border-radius: 12px; padding: 0.75rem; width: 100%; outline: none; transition: border-color 0.2s; }
  .code-input:focus { border-color: var(--accent-1); }
  .btn-confirmar { background: var(--accent-1); color: #fff; border: none; border-radius: 12px; padding: 0.75rem 2rem; font-weight: 700; width: 100%; font-size: 1rem; transition: background 0.2s; }
  .btn-confirmar:hover { background: #d4003a; }

  /* FOOTER */
  .footer-mac { background: var(--dark); color: rgba(255,255,255,0.5); text-align: center; padding: 2rem; font-size: 0.82rem; border-top: 2px solid var(--accent-4); }
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
  <a href="#">{{ $obra->genero->nombre ?? '' }}</a>
  <span>›</span>
  <span class="current">{{ $obra->titulo }}</span>
</div>

{{-- ====== DETAIL MAIN ====== --}}
<div class="detail-wrap">
  <div class="row g-5">

    {{-- LEFT: IMÁGENES --}}
    <div class="col-lg-6 fade-up fade-up-1">
      <div class="img-main-wrap">
        <img id="mainImg"
          src="{{ $obra->image_url ?? 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?w=900&q=85' }}"
          alt="{{ $obra->titulo }}" />
        <span class="img-genre-badge">{{ $obra->genero->nombre ?? '' }}</span>
        <span class="img-status-badge">
          <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i>
          {{ $obra->estado }}
        </span>
        <button class="img-zoom-btn" onclick="document.getElementById('zoomModal').style.display='flex'">
          <i class="bi bi-arrows-fullscreen"></i>
        </button>
      </div>

      {{-- Miniaturas --}}
      <div class="thumbs-row">
        <div class="thumb active"
          onclick="changeImg(this,'{{ $obra->image_url ?? 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?w=900&q=85' }}')">
          <img src="{{ $obra->image_url ?? 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?w=200&q=70' }}" alt="" />
        </div>
        {{-- Si tienes galería adicional, itera aquí --}}
        {{-- @foreach($obra->imagenes as $img)
          <div class="thumb" onclick="changeImg(this,'{{ $img->url }}')">
            <img src="{{ $img->url }}" alt="" />
          </div>
        @endforeach --}}
      </div>
    </div>

    {{-- RIGHT: INFO --}}
    <div class="col-lg-6 info-side fade-up fade-up-2">
      <p class="obra-label">{{ $obra->genero->nombre ?? '' }} · Obra original</p>
      <h1 class="obra-title">{{ $obra->titulo }}</h1>

      {{-- Artista --}}
      <div class="obra-artist-row">
        <img
          src="{{ $obra->artista->foto_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($obra->artista->nombre) . '&background=3A86FF&color=fff' }}"
          class="artist-avatar" alt="{{ $obra->artista->nombre }}" />
        <div class="artist-info-text">
          <a href="{{ route('catalogo.biografia', $obra->artista->id) }}" class="artist-name-link">
            {{ $obra->artista->nombre }}
          </a>
          <div class="artist-nationality">{{ $obra->artista->nacionalidad }}</div>
        </div>
      </div>

      {{-- Estado timeline --}}
      <div class="status-row mt-4">
        <div class="status-step">
          <div class="status-circle done"><i class="bi bi-check"></i></div>
          <div class="status-label">Registrada</div>
        </div>
        <div class="status-line {{ in_array($obra->estado, ['Reservada','Vendida']) ? 'done' : '' }}"></div>
        <div class="status-step">
          <div class="status-circle {{ $obra->estado === 'Reservada' ? 'current' : ($obra->estado === 'Vendida' ? 'done' : 'pending') }}">
            @if($obra->estado === 'Vendida') <i class="bi bi-check"></i> @else 2 @endif
          </div>
          <div class="status-label {{ $obra->estado === 'Reservada' ? 'active-label' : '' }}">Reservada</div>
        </div>
        <div class="status-line {{ $obra->estado === 'Vendida' ? 'done' : '' }}"></div>
        <div class="status-step">
          <div class="status-circle {{ $obra->estado === 'Vendida' ? 'done' : 'pending' }}">
            @if($obra->estado === 'Vendida') <i class="bi bi-check"></i> @else 3 @endif
          </div>
          <div class="status-label {{ $obra->estado === 'Vendida' ? 'active-label' : '' }}">Vendida</div>
        </div>
      </div>

      {{-- Precio y botón --}}
      <div class="price-block">
        <div class="price-label">Precio de venta</div>
        <div class="price-value">${{ number_format($obra->precio_venta, 0, ',', '.') }}</div>
        <div class="price-note">
          + IVA 16% · Comisión museo: <strong>{{ $obra->comision ?? 8 }}%</strong>
        </div>

        @if($obra->estado === 'Disponible')
@auth
    <a href="{{ route('catalogo.validacion', $obra->id) }}" class="btn-comprar-main" style="text-decoration:none">
        <i class="bi bi-bag-check"></i> Comprar esta obra
    </a>
@else
            <a href="{{ route('login') }}" class="btn-comprar-main" style="text-decoration:none">
              <i class="bi bi-lock"></i> Inicia sesión para comprar
            </a>
          @endauth
          <button class="btn-wishlist">
            <i class="bi bi-heart"></i> Guardar en favoritos
          </button>
        @else
          <button class="btn-comprar-main" disabled style="opacity:0.5;cursor:not-allowed;">
            <i class="bi bi-x-circle"></i>
            {{ $obra->estado === 'Reservada' ? 'Obra Reservada' : 'Obra Vendida' }}
          </button>
        @endif
      </div>

      {{-- TABS: Ficha técnica / Descripción / Envío --}}
      <div class="tabs-nav">
        <button class="tab-btn active" onclick="showTab('ficha', this)">Ficha Técnica</button>
        <button class="tab-btn" onclick="showTab('desc', this)">Descripción</button>
        <button class="tab-btn" onclick="showTab('envio', this)">Envío</button>
      </div>

      <div class="tab-content active" id="tab-ficha">
        <table class="specs-table">
          <tr><td>Título</td><td>{{ $obra->titulo }}</td></tr>
          <tr><td>Artista</td><td>{{ $obra->artista->nombre }}</td></tr>
          <tr><td>Género</td><td>{{ $obra->genero->nombre ?? '—' }}</td></tr>
          <tr><td>Material / Técnica</td><td>{{ $obra->material ?? '—' }}</td></tr>
          <tr><td>Dimensiones</td><td>{{ $obra->dimensiones ?? '—' }}</td></tr>
          <tr><td>Peso</td><td>{{ $obra->peso ? $obra->peso . ' kg' : '—' }}</td></tr>
          <tr><td>Año</td><td>{{ $obra->anio ?? '—' }}</td></tr>
          <tr><td>Estado</td><td>{{ $obra->estado }}</td></tr>
          <tr><td>Comisión del museo</td><td>{{ $obra->comision ?? 8 }}% sobre el precio de venta</td></tr>
        </table>
      </div>

      <div class="tab-content" id="tab-desc">
        <p style="font-size:0.9rem; line-height:1.8; color:#555;">
          {{ $obra->descripcion ?? 'Sin descripción disponible.' }}
        </p>
      </div>

      <div class="tab-content" id="tab-envio">
        <table class="specs-table">
          <tr><td>Embalaje</td><td>Caja de madera acolchada</td></tr>
          <tr><td>Seguro de transporte</td><td>Incluido (valor declarado)</td></tr>
          <tr><td>Tiempo estimado</td><td>10 – 20 días hábiles</td></tr>
          <tr><td>Cobertura</td><td>Nacional e internacional</td></tr>
          <tr><td>Responsable del envío</td><td>Museo de Arte Contemporáneo</td></tr>
        </table>
      </div>
    </div>

  </div>
</div>

{{-- ====== OBRAS RELACIONADAS ====== --}}
<div class="related-section">
  <h2 class="section-title-rel fade-up fade-up-3">
    Otras obras de {{ $obra->artista->nombre }}
  </h2>
  <div class="row g-4">
    @forelse($obrasRelacionadas as $rel)
      <div class="col-6 col-lg-3 fade-up fade-up-1">
        <div class="rel-card">
          <img src="{{ $rel->image_url ?? 'https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?w=400&q=75' }}"
               alt="{{ $rel->titulo }}" />
          <div class="rel-card-body">
            <div class="rel-title">{{ $rel->titulo }}</div>
            <a href="{{ route('obra.detalle', $rel->id) }}" class="rel-artist">
              {{ $rel->artista->nombre }}
            </a>
            <div class="rel-price">${{ number_format($rel->precio, 0, ',', '.') }}</div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <p style="color:#aaa; font-size:0.875rem;">No hay otras obras de este artista disponibles.</p>
      </div>
    @endforelse
  </div>
</div>

{{-- ====== FOOTER ====== --}}

        
      </div>
    </div>
  </div>
</div>

{{-- ====== ZOOM MODAL ====== --}}
<div id="zoomModal"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.92); z-index:9999; align-items:center; justify-content:center;"
     onclick="this.style.display='none'">
  <img id="zoomImg"
       src="{{ $obra->image_url ?? 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?w=1200&q=90' }}"
       style="max-width:90vw; max-height:90vh; border-radius:12px; object-fit:contain;" alt="" />
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function showTab(id, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-' + id).classList.add('active');
  }

  function changeImg(thumb, src) {
    document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
    document.getElementById('mainImg').src = src;
    document.getElementById('zoomImg').src = src.replace('w=900','w=1200');
  }
</script>

@endsection

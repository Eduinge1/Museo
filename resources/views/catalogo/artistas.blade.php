@extends('layouts.app')

@section('title', 'Nuestros Artistas — Museo de Arte Contemporáneo')

@section('content')
<style>
    .artists-hero {
        background: var(--dark);
        padding: 5rem 2rem;
        color: #fff;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .artists-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 50% 50%, rgba(131, 56, 236, 0.2) 0%, transparent 70%);
    }
    .artist-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #eee;
        height: 100%;
        transition: all 0.3s;
    }
    .artist-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    }
    .artist-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }
    .artist-body { padding: 1.5rem; text-align: center; }
    .artist-name { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; color: var(--dark); }
    .artist-meta { font-size: 0.85rem; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1rem; }
    .work-count { background: var(--light); padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.8rem; font-weight: 600; color: var(--accent-4); }
</style>

{{-- NAVBAR --}}
<nav class="navbar-mac d-flex justify-content-between align-items-center" style="background: var(--dark); padding: 0.85rem 2rem; border-bottom: 2px solid var(--accent-1);">
  <a href="{{ route('home') }}" class="navbar-brand-custom" style="font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 900; color: #fff; text-decoration: none;">MAC <span>·</span> Arte</a>
  <div class="d-flex align-items-center gap-3">
    <a href="{{ route('home') }}" style="color: rgba(255,255,255,0.75); text-decoration: none; font-size: 0.875rem;">Catálogo</a>
    <a href="{{ route('catalogo.artistas') }}" style="color: var(--accent-2); text-decoration: none; font-size: 0.875rem; font-weight: 600;">Artistas</a>
    <a href="{{ route('catalogo.generos') }}" style="color: rgba(255,255,255,0.75); text-decoration: none; font-size: 0.875rem;">Géneros</a>
  </div>
</nav>

<header class="artists-hero">
    <div class="container relative" style="z-index: 1;">
        <span style="color: var(--accent-2); font-size: 0.75rem; letter-spacing: 4px; text-transform: uppercase;">Excelencia Creativa</span>
        <h1 style="font-family: 'Playfair Display', serif; font-size: 3.5rem; font-weight: 900; margin-top: 1rem;">Nuestros Artistas</h1>
        <p style="color: rgba(255,255,255,0.6); max-width: 600px; margin: 1.5rem auto 0;">Conoce a las mentes brillantes detrás de nuestra colección exclusiva. Visionarios que transforman la realidad en arte.</p>
    </div>
</header>

<div class="container py-5">
    <div class="row g-4">
        @foreach($artistas as $artista)
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('catalogo.biografia', $artista->id) }}" class="text-decoration-none">
                <div class="artist-card">
                    <img src="{{ $artista->image_url ?? 'https://via.placeholder.com/400x500?text=' . urlencode($artista->nombre) }}" alt="{{ $artista->nombre }}" class="artist-img">
                    <div class="artist-body">
                        <div class="artist-meta">{{ $artista->nacionalidad }}</div>
                        <h3 class="artist-name">{{ $artista->nombre }}</h3>
                        <div class="mt-3">
                            <span class="work-count">{{ $artista->obras_count }} Obras</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

<footer style="background: var(--dark); color: rgba(255,255,255,0.5); text-align: center; padding: 2rem; font-size: 0.82rem; border-top: 2px solid var(--accent-4); margin-top: 4rem;">
  <p>© {{ date('Y') }} <strong>Museo de Arte Contemporáneo</strong></p>
</footer>
@endsection

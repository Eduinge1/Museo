@extends('layouts.app')

@section('title', 'Géneros y Estilos — Museo de Arte Contemporáneo')

@section('content')
<style>
    .genres-hero {
        background: var(--dark);
        padding: 5rem 2rem;
        color: #fff;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .genres-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 20% 50%, rgba(255, 77, 109, 0.15) 0%, transparent 70%);
    }
    .genre-card {
        background: #fff;
        border-radius: 20px;
        padding: 2.5rem 1.5rem;
        text-align: center;
        border: 1px solid #eee;
        height: 100%;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .genre-card:hover {
        transform: scale(1.03);
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        border-color: var(--accent-3);
    }
    .genre-icon {
        width: 70px;
        height: 70px;
        background: var(--light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--accent-3);
        margin-bottom: 1.5rem;
        transition: all 0.3s;
    }
    .genre-card:hover .genre-icon {
        background: var(--accent-3);
        color: #fff;
    }
    .genre-name { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; color: var(--dark); margin-bottom: 0.5rem; }
    .genre-count { font-size: 0.85rem; color: #888; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; }
</style>

{{-- NAVBAR --}}
<nav class="navbar-mac d-flex justify-content-between align-items-center" style="background: var(--dark); padding: 0.85rem 2rem; border-bottom: 2px solid var(--accent-1);">
  <a href="{{ route('home') }}" class="navbar-brand-custom" style="font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 900; color: #fff; text-decoration: none;">MAC <span>·</span> Arte</a>
  <div class="d-flex align-items-center gap-3">
    <a href="{{ route('home') }}" style="color: rgba(255,255,255,0.75); text-decoration: none; font-size: 0.875rem;">Catálogo</a>
    <a href="{{ route('catalogo.artistas') }}" style="color: rgba(255,255,255,0.75); text-decoration: none; font-size: 0.875rem;">Artistas</a>
    <a href="{{ route('catalogo.generos') }}" style="color: var(--accent-2); text-decoration: none; font-size: 0.875rem; font-weight: 600;">Géneros</a>
  </div>
</nav>

<header class="genres-hero">
    <div class="container relative" style="z-index: 1;">
        <span style="color: var(--accent-2); font-size: 0.75rem; letter-spacing: 4px; text-transform: uppercase;">Diversidad Artística</span>
        <h1 style="font-family: 'Playfair Display', serif; font-size: 3.5rem; font-weight: 900; margin-top: 1rem;">Géneros y Estilos</h1>
        <p style="color: rgba(255,255,255,0.6); max-width: 600px; margin: 1.5rem auto 0;">Desde el clasicismo hasta las vanguardias digitales. Explora nuestra colección clasificada por su esencia técnica y temática.</p>
    </div>
</header>

<div class="container py-5">
    <div class="row g-4 justify-content-center">
        @foreach($generos as $genero)
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('home', ['genero' => $genero->id]) }}" class="text-decoration-none">
                <div class="genre-card">
                    <div class="genre-icon">
                        <i class="bi bi-palette2"></i>
                    </div>
                    <h3 class="genre-name">{{ $genero->nombre }}</h3>
                    <span class="genre-count">{{ $genero->obras_count }} Obras</span>
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

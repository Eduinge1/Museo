<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@extends('layouts.app')

@section('title', 'Mi Panel — Museo de Arte Contemporáneo')

@section('content')
<style>
    .dash-hero {
        background: var(--dark);
        padding: 4rem 2rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        border-bottom: 4px solid var(--accent-4);
    }
    .dash-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 80% 20%, rgba(58, 134, 255, 0.15) 0%, transparent 50%);
    }
    .user-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--accent-2);
        margin-bottom: 1rem;
    }
    .welcome-text {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        font-weight: 900;
    }
    .dash-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #eee;
        padding: 2rem;
        height: 100%;
        transition: transform 0.3s;
    }
    .dash-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .icon-blue { background: rgba(58, 134, 255, 0.1); color: var(--accent-3); }
    .icon-pink { background: rgba(255, 77, 109, 0.1); color: var(--accent-1); }
    .icon-yellow { background: rgba(255, 190, 11, 0.1); color: var(--accent-2); }

    .stat-val { font-size: 2rem; font-weight: 700; color: var(--dark); font-family: 'Playfair Display', serif; }
    .stat-label { font-size: 0.85rem; color: #888; text-transform: uppercase; letter-spacing: 1px; }
</style>

<div class="dash-hero">
    <div class="container text-center">
        <span class="user-badge fade-up">Comprador Distinguido</span>
        <h1 class="welcome-text fade-up" style="animation-delay: 0.1s;">¡Hola, {{ auth()->user()->name }}!</h1>
        <p class="text-white-50 mt-3 fade-up" style="animation-delay: 0.2s;">Bienvenido a tu espacio privado en el Museo de Arte Contemporáneo.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Perfil Card -->
        <div class="col-md-4">
            <div class="dash-card">
                <div class="card-icon icon-blue"><i class="bi bi-person-vcard"></i></div>
                <h5 class="fw-bold mb-3">Mi Perfil</h5>
                <div class="mb-2"><strong>Email:</strong> <span class="text-muted">{{ auth()->user()->email }}</span></div>
                <div class="mb-2"><strong>Código de Seguridad:</strong> <code class="text-primary fw-bold">{{ $comprador->codigos_seguridad->hash_code ?? 'No asignado' }}</code></div>
                <div class="mb-4"><strong>Teléfono:</strong> <span class="text-muted">{{ $comprador->telefono ?? 'No registrado' }}</span></div>
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-dark btn-sm rounded-pill px-4">Editar Perfil</a>
            </div>
        </div>

        <!-- Membresía Card -->
        <div class="col-md-4">
            <div class="dash-card text-center">
                <div class="card-icon icon-yellow mx-auto"><i class="bi bi-star-fill"></i></div>
                <h5 class="fw-bold mb-1">Membresía MAC</h5>
                <div class="stat-val mt-2">{{ $comprador->membresias && $comprador->membresias->is_active ? 'Activa' : 'Inactiva' }}</div>
                <p class="stat-label mb-4">Estatus Actual</p>
                <div class="small text-muted mb-3">Vence el: {{ $comprador->membresias ? $comprador->membresias->fecha_expiracion->format('d/m/Y') : '—' }}</div>
            </div>
        </div>

        <!-- Acceso Rápido -->
        <div class="col-md-4">
            <div class="dash-card">
                <div class="card-icon icon-pink"><i class="bi bi-palette"></i></div>
                <h5 class="fw-bold mb-3">Explorar Colección</h5>
                <p class="text-muted small">Descubre nuevas obras y añade piezas únicas a tu colección personal.</p>
                <a href="{{ route('home') }}" class="btn btn-dark w-100 rounded-pill mt-3">Ir al Catálogo</a>
            </div>
        </div>
    </div>

    <!-- Sección de Actividad Reciente -->
    <div class="mt-5 pt-4">
        <h3 class="fw-bold mb-4" style="font-family: 'Playfair Display', serif;">Mi Actividad</h3>
        <div class="bg-white rounded-4 border p-5 text-center text-muted">
            <i class="bi bi-clock-history display-4 mb-3 d-block"></i>
            <p>Todavía no tienes compras o reservas registradas.</p>
            <a href="{{ route('home') }}" class="text-primary text-decoration-none">Empieza a explorar el catálogo hoy mismo</a>
        </div>
    </div>
</div>
@endsection

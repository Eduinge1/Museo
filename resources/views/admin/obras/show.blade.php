@extends('layouts.admin')

@section('title', 'Detalle de Obra — Admin')

@section('topbar_title', 'Detalles de la Obra')
@section('topbar_subtitle', $obra->titulo)

@section('content')
<div class="panel p-4">
    <div class="row">
        <div class="col-md-4 mb-4">
            <img src="{{ $obra->image_url }}" class="img-fluid rounded shadow" alt="{{ $obra->titulo }}">
        </div>
        <div class="col-md-8">
            <h3 class="font-serif fw-bold mb-3">{{ $obra->titulo }}</h3>
            <div class="row g-3">
                <div class="col-6">
                    <p class="text-muted mb-0 small text-uppercase fw-bold">Artista</p>
                    <p class="fs-5">{{ $obra->artista->nombre ?? 'N/A' }}</p>
                </div>
                <div class="col-6">
                    <p class="text-muted mb-0 small text-uppercase fw-bold">Género</p>
                    <p class="fs-5">{{ $obra->genero->nombre ?? 'N/A' }}</p>
                </div>
                <div class="col-6">
                    <p class="text-muted mb-0 small text-uppercase fw-bold">Precio</p>
                    <p class="fs-4 fw-bold text-primary">${{ number_format($obra->precio_venta, 2) }}</p>
                </div>
                <div class="col-6">
                    <p class="text-muted mb-0 small text-uppercase fw-bold">Estado</p>
                    <span class="fs-6 badge rounded-pill {{ $obra->estado == 'Disponible' ? 'bg-success' : ($obra->estado == 'Reservada' ? 'bg-warning' : 'bg-info') }}">
                        {{ $obra->estado }}
                    </span>
                </div>
                <div class="col-12">
                    <p class="text-muted mb-0 small text-uppercase fw-bold">Fecha de Creación</p>
                    <p class="fs-6">{{ \Carbon\Carbon::parse($obra->fecha_creacion)->format('d \d\e F, Y') }}</p>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="d-flex gap-2">
                <a href="{{ route('admin.obras.edit', $obra->id) }}" class="btn btn-dark px-4">
                    <i class="bi bi-pencil me-2"></i> Editar Obra
                </a>
                <a href="{{ route('admin.obras.index') }}" class="btn btn-light border px-4">
                    Volver al Listado
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

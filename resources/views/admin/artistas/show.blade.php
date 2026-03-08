@extends('layouts.admin')

@section('title', 'Detalle del Artista — Admin')

@section('topbar_title', 'Detalle del Artista')
@section('topbar_subtitle', 'Información completa de ' . $artista->nombre)

@section('topbar_actions')
<a href="{{ route('admin.artistas.edit', $artista->id) }}" class="btn-add-secondary text-decoration-none"><i class="bi bi-pencil"></i> Editar</a>
<a href="{{ route('admin.artistas.index') }}" class="btn-add text-decoration-none"><i class="bi bi-arrow-left"></i> Volver</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="panel p-0 overflow-hidden" style="background: #fff; border-radius: 16px; border: 1px solid #eee;">
            <img src="{{ $artista->image_url ?? 'https://via.placeholder.com/400x500?text=Sin+Imagen' }}" class="img-fluid w-100" style="height: 450px; object-fit: cover;" alt="{{ $artista->nombre }}">
            <div class="p-3 text-center">
                <h4 class="mb-0 fw-bold">{{ $artista->nombre }}</h4>
                <p class="text-muted mb-0">{{ $artista->nacionalidad }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel p-4 h-100" style="background: #fff; border-radius: 16px; border: 1px solid #eee;">
            <h5 class="fw-bold mb-4 border-bottom pb-2">Información Biográfica</h5>
            <div class="row mb-4">
                <div class="col-sm-4 text-muted">Nombre Completo:</div>
                <div class="col-sm-8 fw-medium">{{ $artista->nombre }}</div>
            </div>
            <div class="row mb-4">
                <div class="col-sm-4 text-muted">Nacionalidad:</div>
                <div class="col-sm-8 fw-medium">{{ $artista->nacionalidad }}</div>
            </div>
            <div class="row mb-4">
                <div class="col-sm-4 text-muted">Fecha de Nacimiento:</div>
                <div class="col-sm-8 fw-medium">{{ $artista->fecha_nacimiento ? $artista->fecha_nacimiento->format('d M, Y') : 'No registrada' }}</div>
            </div>
            <div class="row mb-4">
                <div class="col-sm-4 text-muted">Fecha de Defunción:</div>
                <div class="col-sm-8 fw-medium">{{ $artista->fecha_defuncion ? $artista->fecha_defuncion->format('d M, Y') : 'Sigue Vivo / Actualidad' }}</div>
            </div>
            <div class="row mb-4">
                <div class="col-sm-4 text-muted">Géneros Asociados:</div>
                <div class="col-sm-8 fw-medium">
                    @foreach($artista->generos as $genero)
                        <span class="badge bg-light text-dark border me-1">{{ $genero->nombre }}</span>
                    @endforeach
                </div>
            </div>

            <h5 class="fw-bold mt-5 mb-4 border-bottom pb-2">Obras Registradas ({{ $artista->obras->count() }})</h5>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Obra</th>
                            <th>Estado</th>
                            <th>Precio</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($artista->obras as $obra)
                        <tr>
                            <td>{{ $obra->titulo }}</td>
                            <td>{{ $obra->estado }}</td>
                            <td>${{ number_format($obra->precio_venta, 0) }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.obras.show', $obra->id) }}" class="btn btn-sm btn-outline-dark"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">No hay obras registradas para este artista.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

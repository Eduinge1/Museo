@extends('layouts.admin')

@section('title', 'Nueva Obra — Admin')

@section('topbar_title', 'Registrar Nueva Obra')
@section('topbar_subtitle', 'Completa los datos de la obra de arte')

@section('content')
<div class="panel p-4">
    <form action="{{ route('admin.obras.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Título de la Obra</label>
                <input type="text" name="titulo" class="form-control" required placeholder="Ej: Sinfonía en Azul">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Artista</label>
                <select name="id_artista" class="form-select" required>
                    <option value="">Seleccione un artista</option>
                    @foreach($artistas as $artista)
                        <option value="{{ $artista->id }}">{{ $artista->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Género / Categoría</label>
                <select name="id_genero" class="form-select" required>
                    <option value="">Seleccione un género</option>
                    @foreach($generos as $genero)
                        <option value="{{ $genero->id }}">{{ $genero->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Precio de Venta ($)</label>
                <input type="number" name="precio_venta" class="form-control" required min="0" step="0.01">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Fecha de Creación</label>
                <input type="date" name="fecha_creacion" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">URL de la Imagen</label>
                <input type="url" name="image_url" class="form-control" required placeholder="https://ejemplo.com/imagen.jpg">
            </div>
        </div>

        <hr>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.obras.index') }}" class="btn btn-light border">Cancelar</a>
            <button type="submit" class="btn btn-dark">Guardar Obra</button>
        </div>
    </form>
</div>
@endsection

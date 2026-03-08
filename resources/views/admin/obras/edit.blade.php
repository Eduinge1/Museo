@extends('layouts.admin')

@section('title', 'Editar Obra — Admin')

@section('topbar_title', 'Editar Obra')
@section('topbar_subtitle', 'Modifica los datos de: ' . $obra->titulo)

@section('content')
<div class="panel p-4">
    <form action="{{ route('admin.obras.update', $obra->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Título de la Obra</label>
                <input type="text" name="titulo" class="form-control" required value="{{ $obra->titulo }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Artista</label>
                <select name="id_artista" class="form-select" required>
                    @foreach($artistas as $artista)
                        <option value="{{ $artista->id }}" {{ $obra->id_artista == $artista->id ? 'selected' : '' }}>
                            {{ $artista->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Género / Categoría</label>
                <select name="id_genero" class="form-select" required>
                    @foreach($generos as $genero)
                        <option value="{{ $genero->id }}" {{ $obra->id_genero == $genero->id ? 'selected' : '' }}>
                            {{ $genero->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Precio de Venta ($)</label>
                <input type="number" name="precio_venta" class="form-control" required min="0" step="0.01" value="{{ $obra->precio_venta }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Fecha de Creación</label>
                <input type="date" name="fecha_creacion" class="form-control" required value="{{ $obra->fecha_creacion instanceof \DateTime ? $obra->fecha_creacion->format('Y-m-d') : substr($obra->fecha_creacion, 0, 10) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">URL de la Imagen</label>
                <input type="url" name="image_url" class="form-control" required value="{{ $obra->image_url }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Estado</label>
                <select name="estado" class="form-select">
                    <option value="Disponible" {{ $obra->estado == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="Reservada" {{ $obra->estado == 'Reservada' ? 'selected' : '' }}>Reservada</option>
                    <option value="Vendida" {{ $obra->estado == 'Vendida' ? 'selected' : '' }}>Vendida</option>
                </select>
            </div>
        </div>

        <hr>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.obras.index') }}" class="btn btn-light border">Cancelar</a>
            <button type="submit" class="btn btn-dark">Actualizar Obra</button>
        </div>
    </form>
</div>
@endsection

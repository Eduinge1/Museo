@extends('layouts.admin')

@section('title', 'Editar Artista — Admin')

@section('topbar_title', 'Editar Artista')
@section('topbar_subtitle', 'Actualiza la información de ' . $artista->nombre)

@section('content')
<div class="panel p-4" style="background: #fff; border-radius: 16px; border: 1px solid #eee;">
    <form action="{{ route('admin.artistas.update', $artista->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Nombre del Artista</label>
                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $artista->nombre) }}" required>
                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Nacionalidad</label>
                <input type="text" name="nacionalidad" class="form-control @error('nacionalidad') is-invalid @enderror" value="{{ old('nacionalidad', $artista->nacionalidad) }}" required>
                @error('nacionalidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" value="{{ old('fecha_nacimiento', $artista->fecha_nacimiento ? $artista->fecha_nacimiento->format('Y-m-d') : '') }}">
                @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Fecha de Defunción</label>
                <input type="date" name="fecha_defuncion" class="form-control @error('fecha_defuncion') is-invalid @enderror" value="{{ old('fecha_defuncion', $artista->fecha_defuncion ? $artista->fecha_defuncion->format('Y-m-d') : '') }}">
                <div class="form-text">Dejar en blanco si el artista sigue vivo.</div>
                @error('fecha_defuncion') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">Géneros / Especialidades</label>
                <select name="id_genero[]" class="form-select @error('id_genero') is-invalid @enderror" multiple required style="height: 120px;">
                    @php
                        $selectedGeneros = old('id_genero', $artista->generos->pluck('id')->toArray());
                    @endphp
                    @foreach($generos as $genero)
                        <option value="{{ $genero->id }}" {{ in_array($genero->id, $selectedGeneros) ? 'selected' : '' }}>
                            {{ $genero->nombre }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">Mantén presionado Ctrl (o Cmd en Mac) para seleccionar varios.</div>
                @error('id_genero') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">URL de la Imagen (Retrato)</label>
                <input type="url" name="image_url" class="form-control @error('image_url') is-invalid @enderror" value="{{ old('image_url', $artista->image_url) }}">
                @error('image_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <hr>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.artistas.index') }}" class="btn btn-light border">Cancelar</a>
            <button type="submit" class="btn btn-dark">Actualizar Artista</button>
        </div>
    </form>
</div>
@endsection

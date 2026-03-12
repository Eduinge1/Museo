@extends('layouts.admin')

@section('title', 'Obras Reservadas')
@section('topbar_title', 'Gestión de Obras Reservadas')
@section('topbar_subtitle', $obras->count() . ' obras en estado reservado')

@section('content')

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show mx-4 mt-3" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="container-fluid px-4 py-4">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
      @if($obras->isEmpty())
        <div class="text-center py-5 text-muted">
          <i class="bi bi-inbox fs-1"></i>
          <p class="mt-2">No hay obras reservadas en este momento.</p>
        </div>
      @else
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
              <tr>
                <th>Imagen</th>
                <th>Título</th>
                <th>Artista</th>
                <th>Género</th>
                <th>Precio</th>
                <th>Reservada el</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($obras as $obra)
              <tr>
                <td>
                  <img src="{{ asset($obra->image_url) }}" 
                       style="width:60px; height:50px; object-fit:cover; border-radius:8px;">
                </td>
                <td><strong>{{ $obra->titulo }}</strong></td>
                <td>{{ $obra->artista->nombre }}</td>
                <td><span class="badge bg-secondary">{{ $obra->genero->nombre }}</span></td>
                <td>${{ number_format($obra->precio_venta, 0, ',', '.') }}</td>
                <td>{{ $obra->updated_at->format('d/m/Y H:i') }}</td>
                <td class="text-center">

                  {{-- MARCAR COMO VENDIDA --}}
                  <form action="{{ route('admin.obras.cambiarEstado', $obra->id) }}" 
                        method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="estado" value="Vendida">
                    <button type="submit" class="btn btn-success btn-sm"
                      onclick="return confirm('¿Confirmar venta de esta obra?')">
                      <i class="bi bi-check-circle"></i> Vendida
                    </button>
                  </form>

                  {{-- DEVOLVER A DISPONIBLE --}}
                  <form action="{{ route('admin.obras.cambiarEstado', $obra->id) }}" 
                        method="POST" class="d-inline ms-1">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="estado" value="Disponible">
                    <button type="submit" class="btn btn-warning btn-sm"
                      onclick="return confirm('¿Devolver esta obra a Disponible?')">
                      <i class="bi bi-arrow-counterclockwise"></i> Disponible
                    </button>
                  </form>

                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
</div>

@endsection
```
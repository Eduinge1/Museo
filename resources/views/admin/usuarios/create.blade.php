@extends('layouts.admin')

@section('title', 'Nuevo Usuario Administrativo — Admin')

@section('topbar_title', 'Registrar Usuario')
@section('topbar_subtitle', 'Asigna nuevos roles de acceso al personal del museo')

@section('content')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="panel p-4" style="background: #fff; border-radius: 20px; border: 1px solid #eee; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                <form action="{{ route('admin.usuarios.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted" style="letter-spacing: 1px;">Nombre Completo</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Ej: Juan Pérez">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted" style="letter-spacing: 1px;">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="juan@museo.com">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted" style="letter-spacing: 1px;">Contraseña</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted" style="letter-spacing: 1px;">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted" style="letter-spacing: 1px;">Rol de Acceso</label>
                        <div class="d-flex gap-3">
                            <div class="flex-fill">
                                <input type="radio" class="btn-check" name="role" id="role_empleado" value="empleado" {{ old('role') === 'empleado' ? 'checked' : '' }} checked>
                                <label class="btn btn-outline-secondary w-100 py-3 rounded-4" for="role_empleado">
                                    <i class="bi bi-person me-2"></i> Empleado
                                </label>
                            </div>
                            <div class="flex-fill">
                                <input type="radio" class="btn-check" name="role" id="role_admin" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }}>
                                <label class="btn btn-outline-dark w-100 py-3 rounded-4" for="role_admin">
                                    <i class="bi bi-shield-lock me-2"></i> Administrador
                                </label>
                            </div>
                        </div>
                        <div class="form-text mt-2 small text-muted">
                            <i class="bi bi-info-circle me-1"></i> Los administradores pueden gestionar facturas, reportes y otros usuarios.
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-light px-4 py-2 border-0 rounded-3">Cancelar</a>
                        <button type="submit" class="btn btn-dark px-5 py-2 rounded-3">
                            <i class="bi bi-person-check me-2"></i> Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

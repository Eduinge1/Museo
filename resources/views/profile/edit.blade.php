@extends('layouts.app')

@section('title', 'Editar Perfil — Museo de Arte Contemporáneo')

@section('content')
<style>
    .profile-hero {
        background: var(--dark);
        padding: 4rem 2rem;
        color: #fff;
        border-bottom: 4px solid var(--accent-3);
    }
    .profile-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #eee;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .form-label {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #555;
    }
    .form-control {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        border: 1.5px solid #eee;
        background: #fdfdfd;
    }
    .form-control:focus {
        border-color: var(--accent-3);
        box-shadow: 0 0 0 4px rgba(58, 134, 255, 0.1);
    }
    .section-title {
        font-family: 'Playfair Display', serif;
        font-weight: 900;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: var(--dark);
    }
</style>

<div class="profile-hero text-center">
    <div class="container">
        <h1 class="display-5 fw-bold" style="font-family: 'Playfair Display', serif;">Ajustes de Perfil</h1>
        <p class="text-white-50">Gestiona tu información personal y seguridad de la cuenta.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            @if(session('status') === 'profile-updated')
                <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> Perfil actualizado con éxito.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Update Profile Information -->
            <div class="profile-card">
                <h2 class="section-title"><i class="bi bi-person-circle me-2 text-primary"></i>Información del Perfil</h2>
                <p class="text-muted small mb-4">Actualiza el nombre, correo electrónico y teléfono de tu cuenta.</p>
                
                <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('patch')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
                        @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    @if($user->role === 'comprador')
                    <div class="mb-4">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono', $user->comprador->telefono ?? '') }}">
                        @error('telefono') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    @endif

                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-dark rounded-pill px-4">Guardar Cambios</button>
                    </div>
                </form>
            </div>

            <!-- Update Password -->
            <div class="profile-card">
                <h2 class="section-title"><i class="bi bi-shield-lock me-2 text-warning"></i>Cambiar Contraseña</h2>
                <p class="text-muted small mb-4">Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mantenerla segura.</p>
                
                <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label for="update_password_current_password" class="form-label">Contraseña Actual</label>
                        <input type="password" name="current_password" id="update_password_current_password" class="form-control" autocomplete="current-password">
                        @error('current_password', 'updatePassword') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="update_password_password" class="form-label">Nueva Contraseña</label>
                        <input type="password" name="password" id="update_password_password" class="form-control" autocomplete="new-password">
                        @error('password', 'updatePassword') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="update_password_password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" id="update_password_password_confirmation" class="form-control" autocomplete="new-password">
                        @error('password_confirmation', 'updatePassword') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-dark rounded-pill px-4">Actualizar Contraseña</button>
                        @if (session('status') === 'password-updated')
                            <p class="text-success small mb-0">Guardado.</p>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Delete User -->
            <div class="profile-card border-danger">
                <h2 class="section-title text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Eliminar Cuenta</h2>
                <p class="text-muted small mb-4">Una vez que se elimine tu cuenta, todos sus recursos y datos se eliminarán de forma permanente.</p>
                
                <button type="button" class="btn btn-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
                    Eliminar Cuenta
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Modal Deletetion -->
<div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">¿Estás seguro de que quieres eliminar tu cuenta?</h5>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Por favor, introduce tu contraseña para confirmar que quieres eliminar tu cuenta de forma permanente.</p>
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                    @error('password', 'userDeletion') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Eliminar Definitivamente</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

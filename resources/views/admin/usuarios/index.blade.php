@extends('layouts.admin')

@section('title', 'Gestión de Usuarios — Admin')

@section('topbar_title', 'Usuarios Administrativos')
@section('topbar_subtitle', 'Gestiona empleados y administradores del sistema')

@section('topbar_actions')
<a href="{{ route('admin.usuarios.create') }}" class="btn-topbar-action text-decoration-none">
    <i class="bi bi-person-plus"></i> Nuevo Usuario
</a>
@endsection

@section('extra_css')
<style>
    .user-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #eee;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s;
    }
    .user-card:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.05);
        transform: translateY(-2px);
    }
    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--accent-4), var(--accent-1));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 1.2rem;
    }
    .user-info { flex: 1; }
    .user-name { font-weight: 600; color: var(--dark); font-size: 1rem; margin-bottom: 0.1rem; }
    .user-email { font-size: 0.8rem; color: #aaa; }
    
    .role-badge {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
    }
    .badge-admin { background: rgba(131,56,236,0.1); color: var(--accent-4); border: 1px solid rgba(131,56,236,0.2); }
    .badge-empleado { background: rgba(58,134,255,0.1); color: var(--accent-3); border: 1px solid rgba(58,134,255,0.2); }

    .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 0.3rem; }
    .status-active { background: var(--success); box-shadow: 0 0 10px rgba(6,214,160,0.4); }

    .btn-delete {
        background: none;
        border: none;
        color: #ddd;
        font-size: 1.2rem;
        cursor: pointer;
        transition: color 0.2s;
    }
    .btn-delete:hover { color: var(--accent-1); }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row g-4">
        @foreach($usuarios as $usuario)
        <div class="col-md-6 col-lg-4">
            <div class="user-card">
                <div class="user-avatar" style="{{ $usuario->role === 'admin' ? 'background: linear-gradient(135deg, var(--accent-4), #c77dff)' : '' }}">
                    {{ strtoupper(substr($usuario->name, 0, 2)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ $usuario->name }}</div>
                    <div class="user-email">{{ $usuario->email }}</div>
                    <div class="mt-2 d-flex align-items:center gap-2">
                        @if($usuario->role === 'admin')
                            <span class="role-badge badge-admin">Administrador</span>
                        @else
                            <span class="role-badge badge-empleado">Empleado</span>
                        @endif
                        <span class="text-muted" style="font-size: 0.75rem;">
                            <span class="status-dot status-active"></span> Activo
                        </span>
                    </div>
                </div>
                @if($usuario->id !== auth()->id())
                <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este acceso administrativo?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete" title="Eliminar Acceso">
                        <i class="bi bi-trash3"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

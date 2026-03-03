<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\ObraController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- CATÁLOGO PÚBLICO ---
// Raíz del sitio carga el catálogo (Pantalla 1)
Route::get('/', [CatalogoController::class, 'index'])->name('home');
Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');

// Detalle de obra (Pantalla 2)
Route::get('/catalogo/{id}', [CatalogoController::class, 'show'])->name('obra.detalle');

// Pantalla de validación (cuando el usuario ya seleccionó una obra)
Route::get('/catalogo/{obra}/validar', function($obraId) {
    $obra = \App\Models\Obra::with(['artista','genero'])->findOrFail($obraId);
    return view('catalogo.validacion', compact('obra'));
})->middleware('auth')->name('catalogo.validacion');

// Biografía del artista
Route::get('/artista/{id}', [CatalogoController::class, 'biografia'])->name('catalogo.biografia');

// Recuperación de código y seguridad
Route::get('/auth/recuperacion', fn() => view('auth.recuperacion'))->name('auth.recuperacion');
Route::post('/auth/verificar-respuestas', [AuthController::class,
'verificarRespuestas'])->name('auth.verificar.respuestas');


// --- RUTAS DE USUARIO AUTENTICADO ---
Route::middleware(['auth', 'verified'])->group(function () {

    // Redirección o vista de Dashboard según Rol
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->hasRole('comprador')) {
            $comprador = $user->comprador;
            return view('dashboard', compact('comprador'));
        }
        if ($user->hasRole('empleado')) {
            return redirect()->route('empleado.dashboard');
        }
        if ($user->hasRole('administrador')) {
            return redirect()->route('administrador.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');

    // Dashboard específico para EMPLEADOS
    Route::get('/empleado/dashboard', function () {
        $empleado = Auth::user()->empleado;
        return view('empleado.dashboard', compact('empleado'));
    })->middleware('role:empleado')->name('empleado.dashboard');

    // Dashboard específico para ADMINISTRADORES
    Route::get('/administrador/dashboard', function () {
        $admin = Auth::user()->empleado; // Los admins también son empleados
        return view('administrador.dashboard', compact('admin'));
    })->middleware('role:administrador')->name('administrador.dashboard');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Acción de Reservar (Botón Confirmar Reserva)
    Route::post('/catalogo/{obra}/reservar', [CatalogoController::class,
'reservarObra'])->name('catalogo.reservar');
});

// Rutas de Artworks
Route::resource('artworks', ArtworkController::class);

// --- RUTAS ADMINISTRATIVAS ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Panel Principal Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // CRUD de Obras
    Route::resource('obras', ObraController::class);

    // Módulo de Facturación
    Route::get('/facturacion/nueva', [FacturaController::class, 'create'])->name('facturas.create');
    Route::post('/facturacion', [FacturaController::class, 'store'])->name('facturas.store');
    Route::get('/facturacion/{factura}', [FacturaController::class, 'show'])->name('facturas.show');

    // Módulo de Reportes
    Route::get('/reportes/ventas', [ReporteController::class, 'obrasVendidas'])->name('reportes.ventas');
    Route::get('/reportes/financiero', [ReporteController::class,
'resumenFacturacion'])->name('reportes.financiero');
    Route::get('/reportes/membresias', [ReporteController::class,
'resumenMembresias'])->name('reportes.membresias');
});

require __DIR__.'/auth.php';
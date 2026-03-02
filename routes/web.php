<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\ObraController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ArtworkController;

// --- CATÁLOGO PÚBLICO ---
// Ahora la raíz del sitio carga directamente tu Pantalla 1
Route::get('/', [CatalogoController::class, 'index'])->name('home');


//  muestra cuando el usuario ya seleccionó una obra
Route::get('/catalogo/{obra}/validar', function($obraId) {
    $obra = \App\Models\Obra::with(['artista','genero'])->findOrFail($obraId);
    return view('catalogo.validacion', compact('obra'));
})->middleware('auth')->name('catalogo.validacion');

// Esta ruta carga
Route::get('/catalogo/{id}', [CatalogoController::class, 'show'])->name('obra.detalle');

Route::get('/', [CatalogoController::class, 'index'])->name('home');
Route::get('/catalogo/{id}', [CatalogoController::class, 'show'])->name('obra.detalle');

// 
Route::get('/artista/{id}', [CatalogoController::class, 'biografia'])->name('catalogo.biografia');

//Recuperación de código.
Route::get('/auth/recuperacion', fn() => view('auth.recuperacion'))->name('auth.recuperacion');
Route::post('/auth/verificar-respuestas', [AuthController::class, 'verificarRespuestas'])->name('auth.verificar.respuestas');


// --- RUTAS DE USUARIO AUTENTICADO ---
Route::middleware(['auth', 'verified'])->group(function () {
    
   Route::get('/dashboard', function () {
    return redirect()->route('home');
})->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Acción de Reservar (Botón Confirmar Reserva en Pantalla 2)
    Route::post('/catalogo/{obra}/reservar', [CatalogoController::class, 'reservarObra'])->name('catalogo.reservar');
});


// --- RUTAS ADMINISTRATIVAS (Frontend 2) ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Panel Principal Admin (Tu Pantalla 8)
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // CRUD de Obras (Tu Pantalla 9)
    Route::resource('obras', ObraController::class);

    // Módulo de Facturación (Tu Pantalla 10)
    Route::get('/facturacion/nueva', [FacturaController::class, 'create'])->name('facturas.create');
    Route::post('/facturacion', [FacturaController::class, 'store'])->name('facturas.store');
    Route::get('/facturacion/{factura}', [FacturaController::class, 'show'])->name('facturas.show');

    // Módulo de Reportes (Tu Pantalla 12)
    Route::get('/reportes/ventas', [ReporteController::class, 'obrasVendidas'])->name('reportes.ventas');
    Route::get('/reportes/financiero', [ReporteController::class, 'resumenFacturacion'])->name('reportes.financiero');
    Route::get('/reportes/membresias', [ReporteController::class, 'resumenMembresias'])->name('reportes.membresias');
});

// Rutas adicionales de ejemplo
Route::resource('artworks', ArtworkController::class);

require __DIR__.'/auth.php';
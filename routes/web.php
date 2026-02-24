<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\ObraController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\ReporteController;





// Dashboard para COMPRADORES
Route::get('/dashboard', function () {
    $comprador = Auth::user()->comprador;
    return view('dashboard', compact('comprador'));
})->middleware(['auth', 'verified', 'role:comprador'])->name('dashboard');

// Dashboard para EMPLEADOS
Route::get('/empleado/dashboard', function () {
    $empleado = Auth::user()->empleado;
    return view('empleado.dashboard', compact('empleado'));
})->middleware(['auth', 'verified', 'role:empleado'])->name('empleado.dashboard');

// Dashboard para ADMINISTRADORES
Route::get('/administrador/dashboard', function () {
    $admin = Auth::user()->empleado; // Los admins también son empleados
    return view('administrador.dashboard', compact('admin'));
})->middleware(['auth', 'verified', 'role:administrador'])->name('administrador.dashboard');
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas para el catálogo público 
Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/catalogo/{obra}/reservar', [CatalogoController::class, 'reservarObra'])->name('catalogo.reservar');
});

// Agrupamos todas estas rutas para protegerlas con middleware.
// 'auth' asegura que solo los usuarios logueados puedan entrar.
// prefix('admin') es para que todas las URLs empiecen con /admin, lo cual es común en paneles administrativos.

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // -- Panel Principal --
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // -- CRUD de Obras --
    // Route::resource crea automáticamente las 7 rutas estándar (index, create, store, show, edit, update, destroy)
    Route::resource('obras', ObraController::class);

    // -- Módulo de Facturación  --
    Route::get('/facturacion/nueva', [FacturaController::class, 'create'])->name('facturas.create');
    Route::post('/facturacion', [FacturaController::class, 'store'])->name('facturas.store');
    Route::get('/facturacion/{factura}', [FacturaController::class, 'show'])->name('facturas.show');

    // -- Módulo de Reportes --
    Route::get('/reportes/ventas', [ReporteController::class, 'obrasVendidas'])->name('reportes.ventas');
    Route::get('/reportes/financiero', [ReporteController::class, 'resumenFacturacion'])->name('reportes.financiero');
    Route::get('/reportes/membresias', [ReporteController::class, 'resumenMembresias'])->name('reportes.membresias');

});

require __DIR__.'/auth.php';

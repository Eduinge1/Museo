<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\ObraController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\ArtistaController;
use App\Http\Controllers\UserManagementController;
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

// Listados públicos
Route::get('/artistas', [CatalogoController::class, 'artistas'])->name('catalogo.artistas');
Route::get('/generos', [CatalogoController::class, 'generos'])->name('catalogo.generos');

// Pantalla de validación (cuando el usuario ya seleccionó una obra)
Route::get('/catalogo/{obra}/validar', function($obraId) {
    $obra = \App\Models\Obra::with(['artista','genero'])->findOrFail($obraId);
    return view('catalogo.validacion', compact('obra'));
})->middleware('auth')->name('catalogo.validacion');

// Biografía del artista
Route::get('/artista/{id}', [CatalogoController::class, 'biografia'])->name('catalogo.biografia');

// Recuperación de código y seguridad
Route::get('/auth/recuperacion', [PasswordResetLinkController::class, 'create'])->name('auth.recuperacion');
Route::post('/auth/buscar-preguntas', [PasswordResetLinkController::class, 'buscarPreguntas'])->name('auth.buscar.preguntas');
Route::post('/auth/verificar-respuestas', [PasswordResetLinkController::class, 'verificarRespuestas'])->name('auth.verificar.respuestas');



// --- RUTAS DE USUARIO AUTENTICADO ---
Route::middleware(['auth', 'verified'])->group(function () {

    // Redirección o vista de Dashboard según Rol
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role == 'comprador') {
            $comprador = $user->comprador()
                ->with(['codigos_seguridad', 'membresias', 'ventas.obra.artista'])
                ->first();
            return view('dashboard', compact('comprador'));
        }
        if ($user->role == 'empleado') {
            return redirect()->route('empleado.dashboard');
        }
        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');

    // Dashboard específico para EMPLEADOS
    Route::get('/empleado/dashboard', function () {
        $empleado = Auth::user()->empleado;
        return view('empleado.dashboard', compact('empleado'));
    })->middleware('role:empleado')->name('empleado.dashboard');

    // Dashboard específico para ADMINISTRADORES, me dice el editor que no consigue la ruta, CONFIRMEN!!
    Route::get('/administrador/dashboard', function () {
        $admin = Auth::user()->empleado; // Los admins también son empleados
        return view('administrador.dashboard', compact('admin'));
    })->middleware('role:administrador')->name('administrador.dashboard');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Acción de Reservar (Botón Confirmar Reserva)
   Route::post('/catalogo/{obra}/reservar', [CatalogoController::class, 'reservarObra'])->name('catalogo.reservar');

    
});

// Rutas de Artworks
Route::resource('artworks', ArtworkController::class);

// --- RUTAS ADMINISTRATIVAS ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Panel Principal Admin
    Route::get('/dashboard', function () {
        $admin = Auth::user()->empleado;

        // KPI Data
        $obrasReservadasCount = \App\Models\Venta::where('estado', 'Reservada')->count();
        $obrasVendidasCount = \App\Models\Venta::where('estado', 'Completada')->count();
        $membresiasActivasCount = \App\Models\Membresia::where('is_active', true)->count();
        $ingresosMes = \App\Models\Factura::whereMonth('fecha_facturacion', now()->month)
            ->whereYear('fecha_facturacion', now()->year)
            ->sum('precio_venta');

        // Reservas recientes (Pendientes de Confirmar)
        $reservasPendientes = \App\Models\Venta::with(['obra.artista', 'comprador.user'])
            ->where('estado', 'Reservada')
            ->orderBy('fecha_venta', 'desc')
            ->limit(5)
            ->get();

        // Estado del Catálogo
        $catalogStats = [
            'disponibles' => \App\Models\Obra::where('estado', 'Disponible')->count(),
            'reservadas' => \App\Models\Obra::where('estado', 'Reservada')->count(),
            'vendidas' => \App\Models\Obra::where('estado', 'Vendido')->count(),
        ];
        $totalObras = array_sum($catalogStats);

        // Actividad Reciente (Ventas/Reservas)
        $actividadReciente = \App\Models\Venta::with(['obra', 'comprador.user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Membresías Recientes
        $membresiasRecientes = \App\Models\Comprador::with(['user', 'membresias'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Ganancias Mensuales (últimos 6 meses)
        $gananciasMensuales = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $total = \App\Models\Factura::whereMonth('fecha_facturacion', $month->month)
                ->whereYear('fecha_facturacion', $month->year)
                ->sum('precio_venta');
            $gananciasMensuales[] = [
                'label' => $month->shortMonthName,
                'val' => $total
            ];
        }

        return view('admin.dashboard', compact(
            'admin', 
            'obrasReservadasCount', 
            'obrasVendidasCount', 
            'membresiasActivasCount', 
            'ingresosMes',
            'reservasPendientes',
            'catalogStats',
            'totalObras',
            'actividadReciente',
            'membresiasRecientes',
            'gananciasMensuales'
        ));
    })->name('dashboard');

    // CRUD de Obras
    Route::resource('obras', ObraController::class);

    // Obras Reservadas
Route::get('/obras-reservadas', [ObraController::class, 'reservadas'])->name('obras.reservadas');
Route::patch('/obras/{obra}/cambiar-estado', [ObraController::class, 'cambiarEstado'])->name('obras.cambiarEstado');

    // Módulo de Facturación
    Route::resource('artistas', ArtistaController::class);

    // Gestión de Usuarios
    Route::get('/usuarios', [UserManagementController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/nuevo', [UserManagementController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UserManagementController::class, 'store'])->name('usuarios.store');
    Route::patch('/usuarios/{usuario}', [UserManagementController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [UserManagementController::class, 'destroy'])->name('usuarios.destroy');

    // Módulo de Facturación
    Route::get('/facturacion', [FacturaController::class, 'index'])->name('facturas.index');
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
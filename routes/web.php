<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




require __DIR__.'/auth.php';

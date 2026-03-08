<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empleado;
use App\Models\EmpleadoAdministrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    /**
     * Listado de usuarios administrativos (Empleados y Admins)
     */
    public function index()
    {
        $usuarios = User::whereIn('role', ['admin', 'empleado'])
            ->with(['empleado.administrador'])
            ->orderBy('role', 'asc')
            ->get();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Procesar el registro de un nuevo usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,empleado'],
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Crear el usuario base
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                ]);

                // 2. Crear registro en tabla empleados
                $empleado = Empleado::create([
                    'id_usuario' => $user->id,
                    'fecha_ingreso' => now(),
                ]);

                // 3. Si es rol admin, crear registro en tabla empleados_administradores
                if ($request->role === 'admin') {
                    EmpleadoAdministrador::create([
                        'id_empleado' => $empleado->id,
                        'is_active' => true,
                    ]);
                }
            });

            return redirect()->route('admin.usuarios.index')->with('success', 'Usuario registrado exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error al registrar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar usuario
     */
    public function destroy(User $usuario)
    {
        // Evitar que el admin se elimine a sí mismo
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta administrativa.');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado del sistema.');
    }
}

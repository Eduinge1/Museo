<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Comprador;
use App\Models\Membresia;
use App\Models\CodigoSeguridad;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. VALIDACIÓN COMPLETA (Combinada)
        $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'apellido'          => ['nullable', 'string', 'max:255'],
            'cedula'            => ['nullable', 'string', 'max:20'],
            'telefono'          => ['nullable', 'string', 'max:20'],
            'email'             => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'          => ['required', 'confirmed', Rules\Password::defaults()],
            'direccion'         => ['nullable', 'string', 'max:255'],
            'pregunta_1'        => ['nullable', 'string'],
            'respuesta_1'       => ['nullable', 'string', 'max:255'],
            'pregunta_2'        => ['nullable', 'string'],
            'respuesta_2'       => ['nullable', 'string', 'max:255'],
            'pregunta_3'        => ['nullable', 'string'],
            'respuesta_3'       => ['nullable', 'string', 'max:255'],
            'terminos'          => ['required'],
        ], [
            'name.required'     => 'El nombre es obligatorio.',
            'email.required'    => 'El correo es obligatorio.',
            'email.unique'      => 'Este correo ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed'=> 'Las contraseñas no coinciden.',
            'terminos.required' => 'Debes aceptar los términos y condiciones.',
        ]);

        DB::beginTransaction();

        try {
            // 2. CREAR USUARIO BASE
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'comprador', // Se usa 'role' como campo principal
            ]);

            // 3. PROCESAR PAGO FICTICIO ($10)
            $this->procesarPagoFicticio();

            // 4. CREAR MEMBRESÍA ACTIVA
            $membresia = Membresia::create([
                'is_active' => true,
                'monto' => 10.00,
                'fecha_expiracion' => now()->addMonth(),
            ]);

            // 5. GENERAR CÓDIGO DE SEGURIDAD
            $codigoSeguridad = CodigoSeguridad::create([
                'hash_code' => $this->generarHashCode(),
                'fecha_expiracion' => now()->addDays(30),
            ]);

            // 6. CREAR COMPRADOR VINCULADO
            Comprador::create([
                'id_usuario'          => $user->id,
                'id_codigo_seguridad' => $codigoSeguridad->id,
                'id_membresia'        => $membresia->id,
                'telefono'            => $request->telefono ?? 'Sin especificar',
            ]);

            DB::commit();

            event(new Registered($user));
            Auth::login($user);

            return redirect()->route('home')
                             ->with('success', '¡Bienvenido ' . $request->name . '! Tu cuenta y membresía han sido creadas correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en registro: ' . $e->getMessage());

            return back()->withErrors([
                'error' => 'Hubo un problema al crear la cuenta: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Simulación de pago automático.
     */
    private function procesarPagoFicticio(): void
    {
        Log::info('💰 PAGO SIMULADO AUTOMÁTICO - $10.00 APROBADO');
    }

    /**
     * Genera código de seguridad único.
     */
    private function generarHashCode(): string
    {
        $partes = [
            'MUS',
            strtoupper(substr(bin2hex(random_bytes(2)), 0, 4)),
            strtoupper(substr(bin2hex(random_bytes(2)), 0, 4)),
            strtoupper(substr(bin2hex(random_bytes(2)), 0, 4))
        ];
        return implode('-', $partes);
    }
}
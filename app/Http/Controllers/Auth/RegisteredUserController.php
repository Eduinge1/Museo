<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Comprador;
use App\Models\Empleado;
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
use Illuminate\Support\Facades\Log; // ← ESTA LÍNEA ES LA QUE FALTA

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
     * 
     * FLUJO 1 DEL PDF:
     * 1. Validar solo nombre, email y contraseña
     * 2. Crear usuario
     * 3. Cobro ficticio de $10 (automático, sin datos de tarjeta)
     * 4. Crear membresía activa
     * 5. Generar código de seguridad
     * 6. Crear comprador con todos los IDs redirigidos
     * 7. Login y redirección
     */
    public function store(Request $request): RedirectResponse
    {
        // 📝 VALIDACIÓN - SOLO nombre, email y contraseña
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        \DB::beginTransaction();
        
        try {
            // 1. CREAR USUARIO BASE
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'comprador',
            ]);
            
            Log::info('Usuario creado', ['user_id' => $user->id]);

            // =============================================
            // 🟢 SIMULACIÓN DE PAGO - AUTOMÁTICO
            // =============================================
            // ⬇️ Pago simulado de $10 - SIN DATOS DE TARJETA
            $this->procesarPagoFicticio();
            // ⬆️ FIN DE SIMULACIÓN DE PAGO
            // =============================================
            
            // 3. CREAR MEMBRESÍA ACTIVA
            $membresia = Membresia::create([
                'is_active' => true,
                'monto' => 10.00,
                'fecha_expiracion' => now()->addMonth(),
            ]);
            
            Log::info('Membresía creada', [
                'membresia_id' => $membresia->id,
                'monto' => $membresia->monto,
            ]);

            // 4. GENERAR CÓDIGO DE SEGURIDAD
            $codigoSeguridad = CodigoSeguridad::create([
                'hash_code' => $this->generarHashCode(),
                'fecha_expiracion' => now()->addDays(30),
            ]);
            
            Log::info('Código de seguridad generado', [
                'codigo_id' => $codigoSeguridad->id,
                'hash_code' => $codigoSeguridad->hash_code
            ]);

            // 5. CREAR COMPRADOR - CON VALORES POR DEFECTO PARA TELÉFONO
            $comprador = Comprador::create([
                'id_usuario' => $user->id,
                'id_codigo_seguridad' => $codigoSeguridad->id,
                'id_membresia' => $membresia->id,
                'telefono' => 'Sin especificar', // ← Valor por defecto
            ]);
            
            Log::info('✅ COMPRADOR CREADO CON IDs REDIRIGIDOS', [
                'comprador_id' => $comprador->id,
                'id_usuario' => $comprador->id_usuario,
                'id_membresia' => $comprador->id_membresia,
                'id_codigo_seguridad' => $comprador->id_codigo_seguridad,
            ]);

            \DB::commit();

        } catch (\Exception $e) {
            \DB::rollBack();
            
            Log::error('❌ Error en registro', [
                'error' => $e->getMessage(),
            ]);
            
            return back()->withErrors([
                'error' => 'Hubo un problema: ' . $e->getMessage()
            ]);
        }

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard'));
    }
    
    /**
     * 💳 SIMULACIÓN DE PAGO - SIN DATOS DE TARJETA
     * 
     * Esta función NO requiere datos de tarjeta.
     * Solo simula un cobro de $10 automáticamente.
     */
    private function procesarPagoFicticio(): void
    {
        // =============================================
        // 🟢 SIMULACIÓN DE PAGO AUTOMÁTICO
        // =============================================
        
        // Registra en el log que se realizó un pago simulado
        Log::info('💰 PAGO SIMULADO AUTOMÁTICO', [
            'monto' => '$10.00',
            'estado' => 'APROBADO (SIMULACIÓN)',
            'mensaje' => 'Pago automático sin datos de tarjeta'
        ]);
        
        // =============================================
        // 🟢 FIN DE SIMULACIÓN DE PAGO
        // =============================================
    }
    
    /**
     * Genera código de seguridad (para Flujo 2 - Reserva de obra)
     */
    private function generarHashCode(): string
    {
        // Formato: MUS-XXXX-XXXX-XXXX
        $partes = [
            'MUS',
            strtoupper(substr(bin2hex(random_bytes(2)), 0, 4)),
            strtoupper(substr(bin2hex(random_bytes(2)), 0, 4)),
            strtoupper(substr(bin2hex(random_bytes(2)), 0, 4))
        ];
        
        return implode('-', $partes);
    }
}
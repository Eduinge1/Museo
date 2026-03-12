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
use App\Mail\CodigoSeguridadMail;
use Illuminate\Support\Facades\Mail;

use App\Models\TarjetaCredito;
use App\Models\RespuestaSeguridad;
use App\Models\PreguntaSeguridad;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        $preguntas = PreguntaSeguridad::all();
        return view('auth.register', compact('preguntas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'apellido'          => ['nullable', 'string', 'max:255'],
            'cedula'            => ['nullable', 'string', 'max:20'],
            'telefono'          => ['nullable', 'string', 'max:20'],
            'email'             => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'          => ['required', 'confirmed', Rules\Password::defaults()],
            'direccion'         => ['nullable', 'string', 'max:255'],
            'card_number'       => ['required', 'string'],
            'card_expiry'       => ['required', 'string', 'regex:/^\d{2}\/\d{2}$/'],
            'card_cvv'          => ['required', 'numeric', 'digits_between:3,4'],
            'card_holder'       => ['required', 'string', 'max:255'],
            'id_pregunta_1'     => ['required', 'exists:preguntas_seguridad,id'],
            'respuesta_1'       => ['required', 'string', 'max:255'],
            'id_pregunta_2'     => ['required', 'exists:preguntas_seguridad,id'],
            'respuesta_2'       => ['required', 'string', 'max:255'],
            'id_pregunta_3'     => ['required', 'exists:preguntas_seguridad,id'],
            'respuesta_3'       => ['required', 'string', 'max:255'],
            'terminos'          => ['required'],
        ], [
            'name.required'        => 'El nombre es obligatorio.',
            'email.required'       => 'El correo es obligatorio.',
            'email.unique'         => 'Este correo ya está registrado.',
            'password.required'    => 'La contraseña es obligatoria.',
            'password.confirmed'   => 'Las contraseñas no coinciden.',
            'terminos.required'    => 'Debes aceptar los términos y condiciones.',
            'card_number.required' => 'El número de tarjeta es obligatorio.',
            'card_expiry.required' => 'La fecha de vencimiento es obligatoria.',
            'card_cvv.required'    => 'El CVV es obligatorio.',
            'card_holder.required' => 'El nombre del titular es obligatorio.',
        ]);

        DB::beginTransaction();

        try {
            // 1. CREAR USUARIO
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'comprador',
            ]);

            // 2. PAGO FICTICIO
            $montoMembresia = $request->input('membership_amount', 10.00);
            $this->procesarPagoFicticio($montoMembresia);

            // 3. MEMBRESÍA
            $membresia = Membresia::create([
                'is_active'        => true,
                'monto'            => $montoMembresia,
                'fecha_expiracion' => now()->addMonth(),
            ]);

            // 4. CÓDIGO DE SEGURIDAD
            $codigoSeguridad = CodigoSeguridad::create([
                'hash_code'        => $this->generarHashCode(),
                'fecha_expiracion' => now()->addDays(30),
            ]);

            // 5. COMPRADOR
            $comprador = Comprador::create([
                'id_usuario'          => $user->id,
                'id_codigo_seguridad' => $codigoSeguridad->id,
                'id_membresia'        => $membresia->id,
                'telefono'            => $request->telefono ?? 'Sin especificar',
            ]);

            // 6. TARJETA DE CRÉDITO
            $expiry = explode('/', $request->card_expiry);
            TarjetaCredito::create([
                'id_comprador'     => $comprador->id,
                'tipo_tarjeta'     => $this->detectarTipoTarjeta($request->card_number),
                'nombre_asociado'  => $request->card_holder,
                'mes_vencimiento'  => (int)$expiry[0],
                'anio_vencimiento' => (int)$expiry[1],
                'cvv'              => (int)$request->card_cvv,
            ]);

            // 7. RESPUESTAS DE SEGURIDAD
            for ($i = 1; $i <= 3; $i++) {
                RespuestaSeguridad::create([
                    'id_usuario'  => $user->id,
                    'id_pregunta' => $request->input("id_pregunta_$i"),
                    'respuesta'   => Hash::make($request->input("respuesta_$i")),
                ]);
            }

            DB::commit();

            // 8. ENVIAR CORREO CON CÓDIGO
            Mail::to($user->email)->send(new CodigoSeguridadMail(
                $codigoSeguridad->hash_code,
                $user->name
            ));

            event(new Registered($user));
            Auth::login($user);

            return redirect()->route('home')
                ->with('success', '¡Bienvenido ' . $request->name . '! Tu cuenta ha sido creada. Tu código de seguridad ha sido enviado a tu correo.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en registro: ' . $e->getMessage());

            return back()->withErrors([
                'error' => 'Hubo un problema al crear la cuenta: ' . $e->getMessage()
            ]);
        }
    }

    private function detectarTipoTarjeta($number): string
    {
        $firstDigit = substr(str_replace(' ', '', $number), 0, 1);
        if ($firstDigit == '4') return 'Visa';
        if ($firstDigit == '5') return 'Mastercard';
        return 'Otro';
    }

    private function procesarPagoFicticio($monto = 10.00): void
    {
        Log::info("💰 PAGO SIMULADO AUTOMÁTICO - $$monto APROBADO");
    }

    private function generarHashCode(): string
    {
        return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Models\User;
use App\Models\CodigoSeguridad;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Emails\Mails\CodigoSeguridadMail;


class PasswordResetLinkController extends Controller
{
    /**
     * Muestra la vista principal de recuperación (Paso 1).
     */
    public function create(): View
    {
        return view('auth.recuperacion'); 
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once it has been sent
        // we will examine the response then see the message we need to show to the user.
        $status = \Illuminate\Support\Facades\Password::sendResetLink(
            $request->only('email')
        );

        return $status == \Illuminate\Support\Facades\Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }

    /**
     * Busca las preguntas de seguridad asociadas a un email.
     */
    public function buscarPreguntas(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado.']);
        }

        $respuestas = $user->respuestas_seguridad()->with('preguntas_seguridad')->get();

        if ($respuestas->count() < 3) {
            return response()->json(['success' => false, 'message' => 'No tienes preguntas de seguridad configuradas.']);
        }

        return response()->json([
            'success' => true,
            'preguntas' => [
                ['id' => $respuestas[0]->id, 'pregunta' => $respuestas[0]->preguntas_seguridad->pregunta],
                ['id' => $respuestas[1]->id, 'pregunta' => $respuestas[1]->preguntas_seguridad->pregunta],
                ['id' => $respuestas[2]->id, 'pregunta' => $respuestas[2]->preguntas_seguridad->pregunta],
            ]
        ]);
    }

    /**
     * Procesa la petición AJAX del Paso 2 (Verificar respuestas)
     */
    public function verificarRespuestas(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'respuesta_1' => 'required|string',
            'respuesta_2' => 'required|string',
            'respuesta_3' => 'required|string',
        ]);

        // 1. Buscar al usuario
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado.']);
        }

        // 2. Verificar las respuestas de seguridad almacenadas en la tabla respuestas_seguridad
        $respuestas = $user->respuestas_seguridad;
        if ($respuestas->count() < 3) {
            return response()->json(['success' => false, 'message' => 'No tienes preguntas de seguridad configuradas.']);
        }

        // Verificamos cada respuesta contra el hash guardado
        // Importante: No podemos asumir el orden si el usuario las responde en desorden, 
        // pero en el flujo actual las enviamos en el orden que las recibimos en buscarPreguntas.
        $v1 = Hash::check($request->respuesta_1, $respuestas[0]->respuesta);
        $v2 = Hash::check($request->respuesta_2, $respuestas[1]->respuesta);
        $v3 = Hash::check($request->respuesta_3, $respuestas[2]->respuesta);

        if (!$v1 || !$v2 || !$v3) {
            return response()->json(['success' => false, 'message' => 'Respuestas de seguridad incorrectas.']);
        }

        // 3. Generar el nuevo código (OTP de 6 dígitos numéricos)
        $nuevoCodigo = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT); 

        // 4. Guardar el nuevo código en la tabla codigos_seguridad vinculado al comprador
        $comprador = $user->comprador;
        if ($comprador && $comprador->id_codigo_seguridad) {
            $codigoModel = CodigoSeguridad::find($comprador->id_codigo_seguridad);
            if ($codigoModel) {
                $codigoModel->update(['hash_code' => $nuevoCodigo]);
            }
        } else if ($comprador) {
            // Si por alguna razón no tiene código previo, creamos uno
            $codigoModel = CodigoSeguridad::create([
                'hash_code' => $nuevoCodigo,
                'fecha_expiracion' => now()->addDays(30),
            ]);
            $comprador->update(['id_codigo_seguridad' => $codigoModel->id]);
        }

         // 5. ENVIAR CORREO CON EL NUEVO CÓDIGO
        $enviado = (new CodigoSeguridadMail($nuevoCodigo, $user->name))->send($user->email);

        if (!$enviado) {
            return response()->json([
                'success' => false,
                'message' => 'Tu código fue generado, pero no pudimos enviarlo. Intenta más tarde.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => '¡Listo! Tu nuevo código de seguridad ha sido enviado a tu correo.',
        ]);
    }
}
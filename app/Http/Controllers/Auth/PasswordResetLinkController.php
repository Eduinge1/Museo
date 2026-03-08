<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Models\User;
use App\Models\CodigoSeguridad;
use Illuminate\Support\Str;

class PasswordResetLinkController extends Controller
{
    /**
     * Muestra la vista principal de recuperación (Paso 1).
     */
    public function create(): View
    {
        // Cambiamos 'auth.forgot-password' por tu nueva vista
        return view('auth.recuperacion'); 
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

        // 2. Verificar las respuestas
        // NOTA: Ajusta los nombres de estos campos ('respuesta_1', etc.) según
        // cómo los hayas guardado en tu modelo User o RespuestaSeguridad.
        // Aquí utilizo strtolower y trim para evitar que mayúsculas o espacios rompan la validación.
        $respuestasValidas = (
            strtolower(trim($request->respuesta_1)) === strtolower(trim($user->respuesta_1 ?? '')) &&
            strtolower(trim($request->respuesta_2)) === strtolower(trim($user->respuesta_2 ?? '')) &&
            strtolower(trim($request->respuesta_3)) === strtolower(trim($user->respuesta_3 ?? ''))
        );

        if (!$respuestasValidas) {
            return response()->json(['success' => false, 'message' => 'Respuestas incorrectas.']);
        }

        // 3. Generar el nuevo código (Ej: 6 letras/números aleatorios o solo números)
        // Usaremos 6 caracteres alfanuméricos en mayúscula para que sea fácil de leer
        $nuevoCodigo = strtoupper(Str::random(6)); 

        // 4. Guardar el nuevo código en la tabla codigos_seguridad
        $comprador = $user->comprador;
        if ($comprador) {
            $codigoModel = CodigoSeguridad::where('id_comprador', $comprador->id)->first();
            if ($codigoModel) {
                $codigoModel->update(['codigo' => $nuevoCodigo]);
            } else {
                CodigoSeguridad::create([
                    'id_comprador' => $comprador->id,
                    'codigo' => $nuevoCodigo
                ]);
            }
        }

        // 5. Retornar el código al frontend para que lo muestre
        return response()->json([
            'success' => true,
            'new_code' => $nuevoCodigo
        ]);
    }
}
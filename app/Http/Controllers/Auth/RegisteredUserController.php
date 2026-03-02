<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Muestra el formulario de registro
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Procesa el registro del nuevo comprador
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. VALIDAR todos los campos del formulario
        $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'apellido'          => ['nullable', 'string', 'max:255'],
            'cedula'            => ['nullable', 'string', 'max:20'],
            'telefono'          => ['nullable', 'string', 'max:20'],
            'email'             => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
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
            // Mensajes de error en español
            'name.required'     => 'El nombre es obligatorio.',
            'email.required'    => 'El correo es obligatorio.',
            'email.unique'      => 'Este correo ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed'=> 'Las contraseñas no coinciden.',
            'terminos.required' => 'Debes aceptar los términos y condiciones.',
        ]);

        // 2. CREAR el usuario en la base de datos
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'rol'       => 'comprador', // rol por defecto
        ]);

        // 3. DISPARAR el evento de registro (envía email de verificación)
        event(new Registered($user));

        // 4. INICIAR SESIÓN automáticamente
        Auth::login($user);

        // 5. REDIRIGIR al home con mensaje de éxito
        return redirect()->route('home')
                         ->with('success', '¡Bienvenido ' . $request->name . '! Tu cuenta ha sido creada correctamente.');
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Admin Museo',
            'email'    => 'admin@museo.com',
            'password' => Hash::make('clave1234'),
            'role'     => 'admin', // Asegúrate de que este sea el nombre de la columna en tu tabla
        ]);
    }
}
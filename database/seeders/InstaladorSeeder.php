<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Instalador;

class InstaladorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario admin
        Instalador::create([
            'usuario' => 'admin',
            'nombre' => 'Administrador Sistema',
            'telefono' => '+56912345678',
            'correo' => 'admin@ilesa.com',
            'rut' => '11111111-1',
            'password' => Hash::make('admin123'),
            'activo' => 'S',
            'rol' => 'admin',
        ]);

        // Crear supervisor
        Instalador::create([
            'usuario' => 'supervisor1',
            'nombre' => 'Juan Supervisor',
            'telefono' => '+56923456789',
            'correo' => 'supervisor@ilesa.com',
            'rut' => '22222222-2',
            'password' => Hash::make('supervisor123'),
            'activo' => 'S',
            'rol' => 'supervisor',
        ]);

        // Crear instalador Diego
        Instalador::create([
            'usuario' => 'diego',
            'nombre' => 'Diego Instalador',
            'telefono' => '+56934567890',
            'correo' => 'diego@ilesa.com',
            'rut' => '33333333-3',
            'password' => Hash::make('diego123'),
            'activo' => 'S',
            'rol' => 'instalador',
        ]);

        // Crear más instaladores
        Instalador::create([
            'usuario' => 'jperez',
            'nombre' => 'Juan Pérez García',
            'telefono' => '+56945678901',
            'correo' => 'jperez@ilesa.com',
            'rut' => '44444444-4',
            'password' => Hash::make('password123'),
            'activo' => 'S',
            'rol' => 'instalador',
        ]);

        Instalador::create([
            'usuario' => 'mrodriguez',
            'nombre' => 'María Rodríguez López',
            'telefono' => '+56956789012',
            'correo' => 'mrodriguez@ilesa.com',
            'rut' => '55555555-5',
            'password' => Hash::make('password123'),
            'activo' => 'S',
            'rol' => 'instalador',
        ]);

        Instalador::create([
            'usuario' => 'cgonzalez',
            'nombre' => 'Carlos González Silva',
            'telefono' => '+56967890123',
            'correo' => 'cgonzalez@ilesa.com',
            'rut' => '66666666-6',
            'password' => Hash::make('password123'),
            'activo' => 'S',
            'rol' => 'instalador',
        ]);

        // Crear un instalador inactivo
        Instalador::create([
            'usuario' => 'pmartinez',
            'nombre' => 'Pedro Martínez Rojas',
            'telefono' => '+56978901234',
            'correo' => 'pmartinez@ilesa.com',
            'rut' => '77777777-7',
            'password' => Hash::make('password123'),
            'activo' => 'N',
            'rol' => 'instalador',
        ]);

        $this->command->info('✅ Instaladores creados correctamente');
        $this->command->info('📧 Admin: admin@ilesa.com | Pass: admin123');
        $this->command->info('📧 Supervisor: supervisor@ilesa.com | Pass: supervisor123');
        $this->command->info('📧 Diego: diego@ilesa.com | Pass: diego123');
    }
}
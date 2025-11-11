<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asigna;
use App\Models\Instalador;
use Carbon\Carbon;

class AsignaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener instaladores activos
        $instaladores = Instalador::activo()->get();
        
        if ($instaladores->count() < 2) {
            $this->command->error('❌ No hay suficientes instaladores activos. Ejecuta InstaladorSeeder primero.');
            return;
        }

        // Asignación 1 - Pendiente
        Asigna::create([
            'nota_venta' => 'NV-001',
            'solicita' => 'admin',
            'asignado1' => $instaladores->random()->id,
            'asignado2' => $instaladores->random()->id,
            'fecha_asigna' => Carbon::now()->subDays(5),
            'estado' => 'pendiente',
            'observaciones' => 'Instalación en edificio corporativo',
        ]);

        // Asignación 2 - Aceptada
        Asigna::create([
            'nota_venta' => 'NV-002',
            'solicita' => 'supervisor1',
            'asignado1' => $instaladores->random()->id,
            'asignado2' => $instaladores->random()->id,
            'asignado3' => $instaladores->random()->id,
            'fecha_asigna' => Carbon::now()->subDays(3),
            'fecha_acepta' => Carbon::now()->subDays(2),
            'estado' => 'aceptada',
            'observaciones' => 'Cliente INACAP - Sede Central',
        ]);

        // Asignación 3 - En proceso
        Asigna::create([
            'nota_venta' => 'NV-003',
            'solicita' => 'admin',
            'asignado1' => $instaladores->random()->id,
            'fecha_asigna' => Carbon::now()->subDays(1),
            'fecha_acepta' => Carbon::now(),
            'estado' => 'en_proceso',
            'observaciones' => 'Instalación urgente',
        ]);

        // Asignación 4 - Completada
        Asigna::create([
            'nota_venta' => 'NV-004',
            'solicita' => 'supervisor1',
            'asignado1' => $instaladores->random()->id,
            'asignado2' => $instaladores->random()->id,
            'asignado3' => $instaladores->random()->id,
            'asignado4' => $instaladores->random()->id,
            'fecha_asigna' => Carbon::now()->subDays(10),
            'fecha_acepta' => Carbon::now()->subDays(9),
            'estado' => 'completada',
            'observaciones' => 'Instalación completada exitosamente',
        ]);

        // Asignación 5 - Pendiente (hoy)
        Asigna::create([
            'nota_venta' => 'NV-005',
            'solicita' => 'admin',
            'asignado1' => $instaladores->random()->id,
            'asignado2' => $instaladores->random()->id,
            'fecha_asigna' => Carbon::now(),
            'estado' => 'pendiente',
            'observaciones' => 'Instalación programada para hoy',
        ]);

        $this->command->info('✅ Asignaciones de prueba creadas correctamente');
    }
}
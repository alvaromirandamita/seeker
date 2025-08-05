<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Parking;

class ParkingSeeder extends Seeder
{
    public function run(): void
    {
        $parkings = [
            [
                'nombre' => 'Parking Microcentro',
                'direccion' => 'Av. Corrientes 1234',
                'latitud' => -34.6037389,
                'longitud' => -58.3815704,
            ],
            [
                'nombre' => 'Parking Palermo',
                'direccion' => 'Av. Santa Fe 3400',
                'latitud' => -34.583132,
                'longitud' => -58.424651,
            ],
            [
                'nombre' => 'Parking Recoleta',
                'direccion' => 'Junín 1760',
                'latitud' => -34.588540,
                'longitud' => -58.393206,
            ],
            [
                'nombre' => 'Parking Belgrano',
                'direccion' => 'Av. Cabildo 2300',
                'latitud' => -34.559593,
                'longitud' => -58.456783,
            ],
            [
                'nombre' => 'Parking San Telmo',
                'direccion' => 'Defensa 800',
                'latitud' => -34.618130,
                'longitud' => -58.373707,
            ],
        ];

        foreach ($parkings as $data) {
            Parking::create($data);
        }
    }
}

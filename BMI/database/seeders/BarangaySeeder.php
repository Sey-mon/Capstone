<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barangay;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangays = [
            'Bagong Silang',
            'Calendola',
            'Chrysanthemum',
            'Cuyab',
            'Estrella',
            'Fatima',
            'GSIS',
            'Landayan',
            'Langgam',
            'Laram',
            'Magsaysay',
            'Maharlika',
            'Narra',
            'Nueva',  
            'Pacita 1',
            'Pacita 2',
            'Poblacion',
            'Riverside',
            'Rosario',
            'Sampaguita',
            'San Antonio',
            'San Lorenzo Ruiz',
            'San Roque',
            'San Vicente',
            'Santo Niño',
            'United Bayanihan',
            'United Better Living'
        ];

        foreach ($barangays as $barangayName) {
            Barangay::firstOrCreate(
                ['name' => $barangayName],
                [
                    'name' => $barangayName,
                    'code' => strtoupper(str_replace([' ', '-'], '_', $barangayName)),
                    'municipality_id' => null,
                    'population' => 0,
                    'health_facility' => null,
                    'coordinates' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Successfully seeded ' . count($barangays) . ' barangays!');
    }
}

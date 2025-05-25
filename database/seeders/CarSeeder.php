<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\Modele;
use Carbon\Carbon;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing models (assuming they exist)
        $modeles = Modele::all();
        
        if ($modeles->isEmpty()) {
            $this->command->warn('No models found. Please run MarqueSeeder and ModeleSeeder first.');
            return;
        }

        $cars = [
            [
                'mat' => 'TUN-1234-A',
                'modele_id' => $modeles->random()->id,
                'dpc' => Carbon::parse('2020-03-15'),
                'contract_id' => 0,
                'km' => 45000,
                'price_per_day' => 85.00,
                'status' => 'available',
                'image_url' => null,
            ],
            [
                'mat' => 'TUN-5678-B',
                'modele_id' => $modeles->random()->id,
                'dpc' => Carbon::parse('2019-07-22'),
                'contract_id' => 0,
                'km' => 62000,
                'price_per_day' => 75.00,
                'status' => 'available',
                'image_url' => null,
            ],
            [
                'mat' => 'TUN-9012-C',
                'modele_id' => $modeles->random()->id,
                'dpc' => Carbon::parse('2021-11-08'),
                'contract_id' => 0,
                'km' => 28000,
                'price_per_day' => 95.00,
                'status' => 'available',
                'image_url' => null,
            ],
            [
                'mat' => 'TUN-3456-D',
                'modele_id' => $modeles->random()->id,
                'dpc' => Carbon::parse('2018-05-12'),
                'contract_id' => 0,
                'km' => 78000,
                'price_per_day' => 65.00,
                'status' => 'available',
                'image_url' => null,
            ],
            [
                'mat' => 'TUN-7890-E',
                'modele_id' => $modeles->random()->id,
                'dpc' => Carbon::parse('2022-01-30'),
                'contract_id' => 0,
                'km' => 15000,
                'price_per_day' => 110.00,
                'status' => 'available',
                'image_url' => null,
            ],
            [
                'mat' => 'TUN-2468-F',
                'modele_id' => $modeles->random()->id,
                'dpc' => Carbon::parse('2017-09-18'),
                'contract_id' => 0,
                'km' => 95000,
                'price_per_day' => 55.00,
                'status' => 'maintenance',
                'image_url' => null,
            ],
            [
                'mat' => 'TUN-1357-G',
                'modele_id' => $modeles->random()->id,
                'dpc' => Carbon::parse('2023-04-05'),
                'contract_id' => 0,
                'km' => 8000,
                'price_per_day' => 125.00,
                'status' => 'available',
                'image_url' => null,
            ],
            [
                'mat' => 'TUN-8642-H',
                'modele_id' => $modeles->random()->id,
                'dpc' => Carbon::parse('2020-12-14'),
                'contract_id' => 0,
                'km' => 52000,
                'price_per_day' => 80.00,
                'status' => 'available',
                'image_url' => null,
            ],
        ];

        foreach ($cars as $carData) {
            Car::create($carData);
        }
    }
}
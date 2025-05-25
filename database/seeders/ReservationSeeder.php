<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\Client;
use App\Models\Car;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::all();
        $cars = Car::all();
        
        if ($clients->isEmpty() || $cars->isEmpty()) {
            $this->command->warn('No clients or cars found. Please run ClientSeeder and CarSeeder first.');
            return;
        }

        $reservations = [
            [
                'client_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(8),
                'total_price' => 255.00,
                'status' => 'confirmed',
            ],
            [
                'client_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->addDays(10),
                'end_date' => Carbon::now()->addDays(15),
                'total_price' => 425.00,
                'status' => 'pending',
            ],
            [
                'client_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->subDays(7),
                'total_price' => 240.00,
                'status' => 'completed',
            ],
            [
                'client_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->addDays(2),
                'end_date' => Carbon::now()->addDays(4),
                'total_price' => 170.00,
                'status' => 'confirmed',
            ],
            [
                'client_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->subDays(20),
                'end_date' => Carbon::now()->subDays(18),
                'total_price' => 160.00,
                'status' => 'completed',
            ],
            [
                'client_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(22),
                'total_price' => 595.00,
                'status' => 'pending',
            ],
            [
                'client_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->subDays(2),
                'total_price' => 225.00,
                'status' => 'cancelled',
            ],
            [
                'client_id' => $clients->random()->id,
                'car_id' => $cars->random()->id,
                'start_date' => Carbon::now()->addDays(30),
                'end_date' => Carbon::now()->addDays(35),
                'total_price' => 400.00,
                'status' => 'pending',
            ],
        ];

        foreach ($reservations as $reservationData) {
            Reservation::create($reservationData);
        }
    }
}
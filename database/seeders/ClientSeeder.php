<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Carbon\Carbon;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'f_name' => 'Ahmed',
                'lname' => 'Ben Ali',
                'email' => 'ahmed.benali@email.com',
                'phone' => '+216 20 123 456',
                'cin' => '12345678',
                'adrs' => '123 Avenue Habib Bourguiba, Tunis',
                'b_date' => Carbon::parse('1985-03-15'),
                'permis' => 'TN123456',
                'date_permis' => Carbon::parse('2005-06-20'),
                'notes' => 'Regular customer, prefers automatic transmission',
                'status' => 'active',
                'contract_id' => 0,
            ],
            [
                'f_name' => 'Fatima',
                'lname' => 'Trabelsi',
                'email' => 'fatima.trabelsi@email.com',
                'phone' => '+216 25 987 654',
                'cin' => '87654321',
                'adrs' => '456 Rue de la République, Sfax',
                'b_date' => Carbon::parse('1990-07-22'),
                'permis' => 'TN789012',
                'date_permis' => Carbon::parse('2010-09-15'),
                'notes' => 'Business client, often books for company events',
                'status' => 'active',
                'contract_id' => 1,
            ],
            [
                'f_name' => 'Mohamed',
                'lname' => 'Khelifi',
                'email' => 'mohamed.khelifi@email.com',
                'phone' => '+216 22 456 789',
                'cin' => '11223344',
                'adrs' => '789 Boulevard du 7 Novembre, Sousse',
                'b_date' => Carbon::parse('1978-12-03'),
                'permis' => 'TN345678',
                'date_permis' => Carbon::parse('1998-04-10'),
                'notes' => 'Long-term client, has been with us for 5+ years',
                'status' => 'active',
                'contract_id' => 0,
            ],
            [
                'f_name' => 'Leila',
                'lname' => 'Mansouri',
                'email' => 'leila.mansouri@email.com',
                'phone' => '+216 26 321 654',
                'cin' => '55667788',
                'adrs' => '321 Avenue de la Liberté, Monastir',
                'b_date' => Carbon::parse('1995-05-18'),
                'permis' => 'TN901234',
                'date_permis' => Carbon::parse('2015-08-25'),
                'notes' => 'Young professional, prefers compact cars',
                'status' => 'active',
                'contract_id' => 0,
            ],
            [
                'f_name' => 'Karim',
                'lname' => 'Bouazizi',
                'email' => 'karim.bouazizi@email.com',
                'phone' => '+216 29 147 258',
                'cin' => '99887766',
                'adrs' => '654 Rue Ibn Khaldoun, Kairouan',
                'b_date' => Carbon::parse('1982-11-30'),
                'permis' => 'TN567890',
                'date_permis' => Carbon::parse('2002-12-05'),
                'notes' => 'Frequent traveler, books cars for weekend trips',
                'status' => 'active',
                'contract_id' => 0,
            ],
            [
                'f_name' => 'Sonia',
                'lname' => 'Gharbi',
                'email' => 'sonia.gharbi@email.com',
                'phone' => '+216 23 852 741',
                'cin' => '33445566',
                'adrs' => '987 Avenue Farhat Hached, Gabès',
                'b_date' => Carbon::parse('1988-09-12'),
                'permis' => 'TN234567',
                'date_permis' => Carbon::parse('2008-11-18'),
                'notes' => 'Family client, usually books larger vehicles',
                'status' => 'inactive',
                'contract_id' => 0,
            ],
        ];

        foreach ($clients as $clientData) {
            Client::create($clientData);
        }
    }
}
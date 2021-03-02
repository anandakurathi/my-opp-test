<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $providers = [
            [
                'name' => 'Google',
                'status' => 'A'
            ],
            [
                'name' => 'Snapchat',
                'status' => 'A'
            ]
        ];

        foreach ($providers as $provider) {
            Provider::create($provider);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'Apo',
            'last_name' => 'Papazisis',
            'email' => 'info@apapazisis.de',
        ]);
    }
}

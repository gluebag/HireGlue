<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Nathaniel',
            'last_name' => 'Williams',
            'email' => 'nathaniel@attentiv.dev',
            'password' => Hash::make('propel42'),
            'location' => 'Boca Raton, FL, USA',
            'phone_number' => '(330) 458-9393',
            'linkedin_url' => 'https://linkedin.com/in/attentivnate/',
            'github_url' => 'https://github.com/gluebag',
            'personal_website_url' => 'https://attentiv.dev',
        ]);

        // You can add more users if needed
    }
}

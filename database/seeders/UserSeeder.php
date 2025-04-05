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
            'date_of_birth' => '1992-07-08',
            'profile_photo_url' => 'https://media.licdn.com/dms/image/v2/C4D03AQElW2erCpRQww/profile-displayphoto-shrink_800_800/profile-displayphoto-shrink_800_800/0/1565713960651?e=1749081600&v=beta&t=wdsEcXFxcV7fyUGMVyuF7ZUBfwo-QcspH2kzrO8_9Ps',
            'linkedin_url' => 'https://linkedin.com/in/attentivnate/',
            'github_url' => 'https://github.com/gluebag',
            'personal_website_url' => 'https://attentiv.dev',
        ]);

        // You can add more users if needed
    }
}

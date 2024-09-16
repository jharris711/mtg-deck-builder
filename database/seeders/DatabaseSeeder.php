<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Josh Admin',
            'email' => 'jharriswebdev@gmail.com',
            'password' => bcrypt('Password123'),
            'email_verified_at' => time(),
        ]);


        $this->call(ScryfallCardSeeder::class);
    }
}

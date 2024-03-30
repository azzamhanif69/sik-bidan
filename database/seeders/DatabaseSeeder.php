<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Application;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Application::create([
            'name_app' => 'BidanConnect',
            'description_app' => 'BidanConnect adalah Applikasi untuk bidan',
            'open_days' => '1',
            'close_days' => "5",
            'open_time' => '18:15',
            'close_time' => '21:00',
            'address' => 'Jalan blablabla..'
        ]);
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'image' => 'profil-images/1.jpeg',
            'is_admin' => 1,
            'gender' => 'Laki-Laki',
            'password' => bcrypt('@Admin123')
        ]);

        User::create([
            'name' => 'guest',
            'email' => 'guest@gmail.com',
            'username' => 'guest',
            'image' => 'profil-images/1.jpeg',
            'is_admin' => 2,
            'gender' => 'Laki-Laki',
            'password' => bcrypt('rahasia')
        ]);
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         \App\Models\User::factory()->create([
             'name' => 'Fares',
             'email' => 'fares@test.com',
             'email_verified_at' => now(),
             'password' => Hash::make('password'), // password
             'remember_token' => Str::random(10),
             'role' => 'super_admin'
         ]);

         Post::factory(10)->create();
    }
}

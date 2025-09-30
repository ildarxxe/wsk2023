<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::query()->create([
             'name' => 'demo1',
             'password' => Hash::make('skills2023d1'),
         ]);
        User::query()->create([
            'name' => 'demo2',
            'password' => Hash::make('skills2023d2'),
        ]);
    }
}

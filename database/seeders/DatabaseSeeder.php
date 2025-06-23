<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'q@q.hu',
            'password' => Hash::make('q'),
            'admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Not Admin',
            'email' => 'a@a.hu',
            'password' => Hash::make('a'),
            'admin' => false,
        ]);

        User::factory(10)->create();

        $this->call([
            EnclosureSeeder::class,
            AnimalSeeder::class,
        ]);
    }
}

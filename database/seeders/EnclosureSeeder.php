<?php

namespace Database\Seeders;

use App\Models\Enclosure;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnclosureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        Enclosure::factory()->create([
            'name' => 'Örök vadászmezők',
            'limit' => 300,
        ]);
        $enclosures = Enclosure::factory(10)->create();

        foreach ($enclosures as $enclosure) {
            $caretakers = $users->random(rand(2, 4));
            $enclosure->users()->attach($caretakers);
        }
    }
}

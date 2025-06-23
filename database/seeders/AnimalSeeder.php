<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\Enclosure;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enclosures = Enclosure::where('id', '!=', 1)->get();

        foreach ($enclosures as $enclosure) {
            Animal::factory(rand(2, $enclosure->limit))->create([
                'enclosure_id' => $enclosure->id,
                'is_predator' => random_int(0, 1),
            ]);
        }
    }
}

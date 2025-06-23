<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Animal>
 */
class AnimalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $useSpecies = fake()->boolean();

        $species = ['Oroszlan', 'Tigris', 'Elefant', 'Pingvin', 'Zsiraf', 'Orrszarvu'];
        $chosenSpecies = fake()->randomElement($species);
        $filename = $chosenSpecies . '.jpg';

        return [
            'name' => fake()->name(),
            'species' => $useSpecies ? $chosenSpecies : fake()->sentence(2),
            'is_predator' => fake()->boolean(35),
            'born_at' => fake()->dateTimeBetween(),
            'filename' => $useSpecies ? $filename : null,
            'filename_hash' => $useSpecies ? $filename : null,
        ];
    }
}

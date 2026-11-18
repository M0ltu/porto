<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExperienceFactory extends Factory
{
    protected $model = Experience::class;

    public function definition(): array
    {
        return [
            'period' => '2025 — 2026',
            'title' => fake()->unique()->sentence(3),
            'organization' => fake()->company(),
            'type' => fake()->randomElement(['PLK', 'Organisasi', 'Lomba']),
            'description' => fake()->sentence(10),
            'tags' => ['Laravel', 'Git'],
            'current' => false,
            'sort_order' => 0,
        ];
    }
}

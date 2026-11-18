<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    protected $model = Skill::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'type' => 'hard',
            'value' => fake()->numberBetween(40, 95),
            'note' => fake()->sentence(4),
            'sort_order' => 0,
        ];
    }

    public function soft(): static
    {
        return $this->state(fn (array $attributes) => ['type' => 'soft']);
    }
}

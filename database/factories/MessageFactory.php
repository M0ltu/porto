<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'role' => 'Teman sekelas',
            'email' => null,
            'relation' => fake()->randomElement(['teman', 'guru']),
            'subject' => fake()->sentence(4),
            'body' => fake()->paragraph(3),
            'ratings' => [
                'design' => fake()->numberBetween(3, 5),
                'navigation' => fake()->numberBetween(3, 5),
                'content' => fake()->numberBetween(3, 5),
                'comfort' => fake()->numberBetween(3, 5),
            ],
            'is_read' => false,
            'approved' => fake()->boolean(70),
            'created_at' => now()->subDays(fake()->numberBetween(1, 15)),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => ['approved' => true]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => ['approved' => false]);
    }
}

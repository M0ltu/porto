<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'category' => fake()->randomElement(['Web App', 'Game', 'Dashboard', 'Tools', 'Experiment']),
            'year' => (string) fake()->numberBetween(2024, 2026),
            'featured' => false,
            'summary' => fake()->sentence(12),
            'cover_path' => '/images/demo/project-1.svg',
            'tech' => ['Laravel', 'Tailwind CSS'],
            'sort_order' => 0,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => ['featured' => true]);
    }
}

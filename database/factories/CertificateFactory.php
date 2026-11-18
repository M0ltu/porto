<?php

namespace Database\Factories;

use App\Models\Certificate;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateFactory extends Factory
{
    protected $model = Certificate::class;

    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(4),
            'issuer' => fake()->company(),
            'year' => (string) fake()->numberBetween(2024, 2026),
            'type' => fake()->randomElement(['Sertifikat', 'Prestasi']),
            'image_path' => '/images/demo/certificate.svg',
            'description' => fake()->sentence(8),
            'sort_order' => 0,
        ];
    }
}

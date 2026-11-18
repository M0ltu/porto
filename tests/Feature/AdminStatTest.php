<?php

use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;

it('shows dynamic hero stats on the home page', function () {
    Project::query()->delete();
    Experience::query()->delete();
    Skill::query()->delete();

    Project::query()->create([
        'slug' => 'proyek-pertama',
        'title' => 'Proyek Pertama',
        'category' => 'Web App',
        'year' => '2026',
        'featured' => false,
        'summary' => 'Ringkasan proyek pertama.',
        'tech' => ['Laravel', 'Blade'],
    ]);

    Project::query()->create([
        'slug' => 'proyek-kedua',
        'title' => 'Proyek Kedua',
        'category' => 'Tools',
        'year' => '2025',
        'featured' => false,
        'summary' => 'Ringkasan proyek kedua.',
        'tech' => ['Blade', 'Tailwind CSS'],
    ]);

    Skill::query()->create(['name' => 'Laravel', 'value' => 80, 'type' => 'hard', 'sort_order' => 0]);
    Skill::query()->create(['name' => 'Komunikasi', 'value' => 80, 'type' => 'soft', 'sort_order' => 1]);

    Experience::query()->create([
        'period' => '2026',
        'title' => 'Magang',
        'organization' => 'Perusahaan',
        'type' => 'PLK',
        'description' => 'Pengalaman magang.',
        'tags' => ['Laravel'],
        'current' => true,
        'sort_order' => 0,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Total proyek')
        ->assertSee('>2<', false)
        ->assertSee('Teknologi')
        ->assertSee('>3<', false)
        ->assertSee('Keahlian')
        ->assertSee('>2<', false)
        ->assertSee('Pengalaman')
        ->assertSee('>1<', false);
});

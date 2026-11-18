<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;

it('guards admin pages behind authentication', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    $this->get(route('admin.projects.index'))->assertRedirect(route('login'));
    $this->get(route('admin.settings'))->assertRedirect(route('login'));
});

it('renders admin pages for authenticated users', function () {
    actingAs(User::factory()->create())
        ->get(route('admin.dashboard'))
        ->assertOk();
});

it('stores, updates, and deletes a project', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $payload = [
        'title' => 'Sistem Absensi Sekolah',
        'slug' => 'sistem-absensi-sekolah',
        'category' => 'Web App',
        'year' => '2026',
        'role' => 'Full-stack developer',
        'summary' => 'Aplikasi absensi sederhana untuk sekolah.',
        'tech' => 'Laravel, Tailwind CSS',
        'features' => "Scan QR\nRekap bulanan",
        'featured' => '1',
        'cover' => UploadedFile::fake()->image('cover.jpg'),
    ];

    $response = actingAs($user)->post(route('admin.projects.store'), $payload);

    $project = Project::query()->where('slug', 'sistem-absensi-sekolah')->firstOrFail();

    $response->assertRedirect(route('admin.projects.index'))->assertSessionHas('success');

    expect($project->featured)->toBeTrue();
    expect($project->tech)->toBe(['Laravel', 'Tailwind CSS']);
    expect($project->features)->toBe(['Scan QR', 'Rekap bulanan']);
    expect($project->cover_path)->toStartWith('/storage/uploads/projects/');

    Storage::disk('public')->assertExists(str_replace('/storage/', '', $project->cover_path));

    actingAs($user)
        ->put(route('admin.projects.update', $project), [
            ...$payload,
            'title' => 'Sistem Absensi Digital',
            'featured' => null,
            'cover' => null,
        ])
        ->assertRedirect(route('admin.projects.index'))
        ->assertSessionHas('success');

    $project->refresh();

    expect($project->title)->toBe('Sistem Absensi Digital');
    expect($project->featured)->toBeFalse();

    actingAs($user)
        ->delete(route('admin.projects.destroy', $project))
        ->assertRedirect(route('admin.projects.index'));

    expect(Project::query()->where('slug', 'sistem-absensi-sekolah')->exists())->toBeFalse();
    Storage::disk('public')->assertMissing(str_replace('/storage/', '', $project->cover_path));
});

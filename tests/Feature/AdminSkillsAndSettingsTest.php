<?php

use App\Models\Profile;
use App\Models\Skill;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('adds, edits, and removes a skill', function () {
    $user = User::factory()->create();

    actingAs($user)->post(route('admin.skills.store'), [
        'name' => 'Vue.js',
        'type' => 'hard',
        'value' => '75',
        'note' => 'Dipakai di proyek eksperimen',
    ])->assertRedirect(route('admin.skills.index'))->assertSessionHas('success');

    $skill = Skill::query()->where('name', 'Vue.js')->firstOrFail();

    expect($skill->type)->toBe('hard');
    expect((int) $skill->value)->toBe(75);

    actingAs($user)->put(route('admin.skills.update', $skill), [
        'name' => 'Vue.js',
        'type' => 'soft',
        'value' => '80',
        'note' => 'Ditulis ulang',
    ])->assertRedirect(route('admin.skills.index'))->assertSessionHas('success');

    $skill->refresh();

    expect($skill->type)->toBe('soft');
    expect((int) $skill->value)->toBe(80);

    actingAs($user)->delete(route('admin.skills.destroy', $skill))->assertRedirect(route('admin.skills.index'));

    expect(Skill::query()->whereKey($skill->getKey())->exists())->toBeFalse();
});

it('validates skill input', function () {
    $user = User::factory()->create();

    actingAs($user)->post(route('admin.skills.store'), [
        'name' => '',
        'type' => 'invalid',
        'value' => '500',
    ])->assertRedirect()->assertSessionHasErrors(['name', 'type', 'value']);
});

it('saves profile settings', function () {
    $user = User::factory()->create();

    actingAs($user)->put(route('admin.settings.update'), [
        'name' => 'David Yusuf',
        'email' => 'david@example.test',
        'headline' => 'Siswa RPL yang suka web development',
        'bio' => 'Suka membangun aplikasi web sederhana.',
    ])->assertRedirect(route('admin.settings'))->assertSessionHas('success');

    $profile = Profile::query()->firstOrFail();

    expect($profile->name)->toBe('David Yusuf');
});

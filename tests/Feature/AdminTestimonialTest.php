<?php

use App\Models\Message;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('approves and hides a message as testimonial', function () {
    $user = User::factory()->create();
    $message = Message::query()->where('approved', false)->firstOrFail();

    actingAs($user)->from(route('admin.testimonials.index'))
        ->post(route('admin.messages.approve', $message))
        ->assertRedirect(route('admin.testimonials.index'))
        ->assertSessionHas('success', 'Testimoni disetujui dan tampil di publik.');
    expect($message->refresh()->approved)->toBeTrue();

    actingAs($user)->from(route('admin.testimonials.index'))
        ->post(route('admin.messages.approve', $message))
        ->assertRedirect(route('admin.testimonials.index'))
        ->assertSessionHas('success', 'Testimoni disembunyikan dari halaman publik.');
    expect($message->refresh()->approved)->toBeFalse();
});

it('deletes a testimonial message', function () {
    $user = User::factory()->create();
    $message = Message::query()->firstOrFail();

    actingAs($user)->from(route('admin.testimonials.index'))
        ->delete(route('admin.messages.destroy', $message))
        ->assertRedirect(route('admin.testimonials.index'))
        ->assertSessionHas('success', 'Testimoni dihapus.');

    expect(Message::query()->whereKey($message->getKey())->exists())->toBeFalse();
});

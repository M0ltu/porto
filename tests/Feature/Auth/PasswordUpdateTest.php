<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('password can be updated without current password', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->put('/password', [
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
});

test('password confirmation is required to update password', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->put('/password', [
            'password' => 'new-password',
            'password_confirmation' => 'wrong-confirmation',
        ]);

    $response
        ->assertSessionHasErrorsIn('updatePassword', 'password')
        ->assertRedirect('/profile');
});

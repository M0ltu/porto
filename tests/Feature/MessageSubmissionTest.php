<?php

use App\Models\Message;

function validMessagePayload(): array
{
    return [
        'name' => 'Rina',
        'relation' => 'teman',
        'body' => 'Situsnya rapi dan mudah dinavigasi.',
        'ratings' => [
            'design' => 5,
            'navigation' => 4,
            'content' => 5,
            'comfort' => 4,
        ],
    ];
}

it('stores a public message as unapproved with aspect ratings', function () {
    $this->post(route('messages.store'), validMessagePayload())
        ->assertRedirect(route('messages.create'))
        ->assertSessionHas('success', 'Terima kasih! Kesan Anda menunggu persetujuan admin.');

    $message = Message::query()->where('name', 'Rina')->where('body', 'Situsnya rapi dan mudah dinavigasi.')->firstOrFail();

    expect($message->approved)->toBeFalse();
    expect($message->email)->toBeNull();
    expect($message->relation)->toBe('teman');
    expect($message->ratings)->toBe([
        'design' => 5,
        'navigation' => 4,
        'content' => 5,
        'comfort' => 4,
    ]);
});

it('silently ignores submissions that fill the honeypot field', function () {
    $payload = validMessagePayload() + ['website' => 'https://spam.example.test'];

    $this->post(route('messages.store'), $payload)
        ->assertRedirect(route('messages.create'))
        ->assertSessionHas('success', 'Terima kasih! Kesan Anda menunggu persetujuan admin.');

    expect(Message::query()->where('name', 'Rina')->where('body', 'Situsnya rapi dan mudah dinavigasi.')->exists())->toBeFalse();
});

it('rejects invalid submissions with errors', function () {
    $this->post(route('messages.store'), ['name' => 'Rina'])
        ->assertRedirect()
        ->assertSessionHasErrors(['relation', 'body', 'ratings']);

    expect(Message::query()->count())->toBe(15);
});

it('throttles repeated submissions', function () {
    $route = route('messages.store');

    for ($i = 0; $i < 5; $i++) {
        $this->post($route, [
            'name' => "Rina {$i}",
            'relation' => 'teman',
            'body' => 'Kesan yang valid untuk pengujian throttle.',
            'ratings' => [
                'design' => 5,
                'navigation' => 4,
                'content' => 5,
                'comfort' => 4,
            ],
        ])->assertRedirect();
    }

    $this->post($route, [
        'name' => 'Rina Terakhir',
        'relation' => 'teman',
        'body' => 'Percobaan yang harus dibatasi.',
        'ratings' => [
            'design' => 5,
            'navigation' => 4,
            'content' => 5,
            'comfort' => 4,
        ],
    ])->assertStatus(429);
});

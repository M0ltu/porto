<?php

use App\Models\Message;
use App\Models\Project;

it('renders all public pages', function (string $url) {
    $this->get($url)->assertOk();
})->with([
    '/ — beranda' => ['/'],
    '/tentang' => ['/tentang'],
    '/keahlian' => ['/keahlian'],
    '/proyek' => ['/proyek'],
    '/sertifikat' => ['/sertifikat'],
    '/pengalaman' => ['/pengalaman'],
    '/cv' => ['/cv'],
    '/kontak' => ['/kontak'],
    '/kesan-pesan' => ['/kesan-pesan'],
]);

it('no longer serves the blog pages', function () {
    $this->get('/blog')->assertNotFound();
    $this->get('/blog/apa-saja')->assertNotFound();
});

it('renders a project detail page by slug and 404s for unknown slugs', function () {
    $project = Project::query()->firstOrFail();

    $this->get(route('projects.show', $project->slug))->assertOk();
    $this->get(route('projects.show', 'tidak-ada-slug'))->assertNotFound();
});

it('only shows approved messages on the public testimonial page', function () {
    Message::query()->update(['approved' => false]);

    $this->get(route('messages.create'))->assertOk()->assertDontSee('kata-kata-testimoni-unik');

    Message::query()->firstOrFail()->update(['body' => 'kata-kata-testimoni-unik', 'approved' => true]);

    $this->get(route('messages.create'))->assertOk()->assertSee('kata-kata-testimoni-unik');
});

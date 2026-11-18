<?php

test('public password reset is disabled', function () {
    $this->get('/forgot-password')->assertNotFound();
    $this->post('/forgot-password')->assertNotFound();
    $this->get('/reset-password/fake-token')->assertNotFound();
    $this->post('/reset-password')->assertNotFound();
});

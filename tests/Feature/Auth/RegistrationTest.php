<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertSuccessful();
});

test('new users can register', function () {
    $email = 'test@example.com';

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => $email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('users', [
        'email' => $email,
    ]);
});

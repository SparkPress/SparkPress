<?php

use App\Actions\Authentication\LogoutUser;
use App\Models\User;

it('should call the action', function () {
    $user = User::factory()->create();
    auth()->login($user);

    LogoutUser::partialMock()
        ->shouldReceive('asController')
        ->once();

    $this->get(route('logout'));
});

it("logs the user out", function() {
    $user = User::factory()->create();
    auth()->login($user);

    $this->get(route('logout'));

    expect(auth()->check())->toBeFalse();
});

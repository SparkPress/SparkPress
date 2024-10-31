<?php

use App\Models\User;
use Livewire\Livewire;

it('renders the livewire component', function () {
    $response = $this->get(route("login"));
    $response->assertStatus(200);
    $response->assertSeeLivewire("authentication.login");
});

it("authenticates the user", function() {
    $user = User::factory()->create();
    Livewire::test("authentication.login")
        ->set("email", $user->email)
        ->set("password", "password")
        ->call("authenticate")
        ->assertRedirect(route("home"));

    expect(auth()->check())->toBeTrue()
        ->and(auth()->user()->is($user))->toBeTrue();
});

it("does not allow invalid credentials", function() {
    $user = User::factory()->create();
    Livewire::test("authentication.login")
        ->set("email", $user->email)
        ->set("password", "invalid-password")
        ->call("authenticate")
        ->assertHasErrors(["email"]);
});

it("validates the email field", function() {
    Livewire::test("authentication.login")
        ->set("email", "invalid-email")
        ->set("password", "password")
        ->call("authenticate")
        ->assertHasErrors(["email" => "email"]);
});

it("validates the password field", function() {
    Livewire::test("authentication.login")
        ->set("email", "test@test.com")
        ->call("authenticate")
        ->assertHasErrors(["password" => "required"]);
});

it("redirects the user to the home page if they are already authenticated", function() {
    $user = User::factory()->create();
    auth()->login($user);

    Livewire::test("authentication.login")
        ->assertRedirect(route("home"));
});

it ("handles the remember me functionality", function() {
    $user = User::factory()->create();
    Livewire::test("authentication.login")
        ->set("email", $user->email)
        ->set("password", "password")
        ->set("remember", true)
        ->call("authenticate")
        ->assertRedirect(route("home"));
});

<?php

it("should not use dd or like")
    ->expect("App")
    ->not->toUse(['die', 'dd', 'dump']);

it("should ensure App\Http is only used within App\Http")
    ->expect('App\Http')
    ->toOnlyBeUsedIn('App\Http');

it("should ensure that Actions use the Action trait")
    ->expect('App\Actions')
    ->toUseTrait('Lorisleiva\Actions\Concerns\AsAction');

it("should ensure that Models extend the Eloquent Model")
    ->expect('App\Models')
    ->toExtend('Illuminate\Database\Eloquent\Model');

it("meets the php preset standards")
    ->preset()
    ->php();

it("meets the laravel preset standards")
    ->preset()
    ->laravel();

it("meets the security preset standards")
    ->preset()
    ->security();

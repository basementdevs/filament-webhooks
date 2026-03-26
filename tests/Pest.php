<?php

declare(strict_types=1);

use Basement\Webhooks\Tests\Fixtures\User;
use Basement\Webhooks\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function actingAsAdmin(): User
{
    $user = User::query()->create([
        'name' => 'Admin',
        'email' => 'admin@test.com',
    ]);

    test()->actingAs($user);

    return $user;
}

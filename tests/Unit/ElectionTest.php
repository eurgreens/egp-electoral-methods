<?php

use App\Utils\Election;
use Tests\TestCase;

uses(TestCase::class);

test('it loads candidates keyed by name with zero votes', function () {
    $election = new Election('borda');
    $candidates = $election->getCandidates();

    expect($candidates)
        ->toBeArray()
        ->toHaveKey('Belgium - female')
        ->toHaveKey('Ukraine - male')
        ->and($candidates['Belgium - female']['votes'])->toBe(0)
        ->and($candidates['Ukraine - male']['votes'])->toBe(0)
        ->and(array_is_list($candidates))->toBeFalse();
});

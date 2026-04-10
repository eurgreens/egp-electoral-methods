<?php

use App\Utils\STVElection;
use Tests\TestCase;

uses(TestCase::class);

test('it counts stv ballots and ranks the transferred winners', function () {
    $election = new STVElection(toElect: 8);
    $election->count();

    $candidates = $election->getCandidates();
    $results = $election->getResults();

    expect($candidates['Belgium - female']['votes'])->toBeGreaterThan(0)
        ->and($candidates['Ukraine - male']['votes'])->toBeGreaterThan(0)
        ->and($candidates['Latvia - male']['votes'])->toBeGreaterThan(0)
        ->and($results[0][1])->toBe('Belgium')
        ->and($results[1][1])->toBe('Ukraine')
        ->and($results[2][1])->toBe('Latvia');
});

test('it restricts later stv rounds to female candidates after four men are elected', function () {
    $election = new STVElection(toElect: 8);
    $election->count();

    $topEightResults = array_slice($election->getResults(), 0, 8);
    $topEightNames = array_column($topEightResults, 1);
    $maleCount = count(array_filter($topEightResults, fn (array $candidate): bool => $candidate[2] === 'male'));

    expect($maleCount)->toBeLessThanOrEqual(4)
        ->and($topEightNames)->toContain('Italy')
        ->and($topEightNames)->not->toContain('Germany');
});

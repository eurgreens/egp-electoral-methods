<?php

use App\Utils\IRVElection;
use Tests\TestCase;

uses(TestCase::class);

test('it elects winners by rerunning irv for each seat', function () {
    $election = new IRVElection(toElect: 8, test: true);
    $election->count();

    $topEightNames = array_column(array_slice($election->getResults(), 0, 8), 1);

    expect($topEightNames)->toBe([
        'Ukraine',
        'Belgium',
        'Latvia',
        'Lithuania',
        'Finland',
        'Moldova',
        'Sweden',
        'Cyprus',
    ]);
});

test('it never elects more than half men across the requested seats', function () {
    $election = new IRVElection(toElect: 8, test: true);
    $election->count();

    $topEightResults = array_slice($election->getResults(), 0, 8);
    $maleCount = count(array_filter($topEightResults, fn (array $candidate): bool => $candidate[2] === 'male'));

    expect($maleCount)->toBeLessThanOrEqual(4)
        ->and($topEightResults[7][2])->toBe('male');
});

<?php

use App\Utils\MajorityJudgementElection;
use Tests\TestCase;

uses(TestCase::class);

test('it ranks candidates using majority judgement grades', function () {
    $election = new MajorityJudgementElection(toElect: 8);
    $election->addMajorityJudgementPoints();

    $candidates = $election->getCandidates();
    $results = $election->getResults();

    expect($candidates['Ukraine - male']['votes'])->toBeGreaterThan($candidates['Finland - female']['votes'])
        ->and($candidates['Finland - female']['votes'])->toBeGreaterThan($candidates['Belgium - female']['votes'])
        ->and($candidates['Belgium - female']['votes'])->toBeGreaterThan($candidates['Croatia - male']['votes'])
        ->and($results[0][1])->toBe('Ukraine')
        ->and($results[1][1])->toBe('Finland')
        ->and($results[2][1])->toBe('Moldova');
});

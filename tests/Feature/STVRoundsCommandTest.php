<?php

test('it outputs stv results by round with explanations', function () {
    $this->artisan('run:stv-rounds')
        ->expectsOutputToContain('Single Transferable Vote by Round')
        ->expectsOutputToContain('Round 1')
        ->expectsOutputToContain('Action:')
        ->expectsOutputToContain('quota')
        ->expectsOutputToContain('Belgium')
        ->assertExitCode(0);
});

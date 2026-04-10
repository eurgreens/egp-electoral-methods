<?php

namespace App\Console\Commands;

use App\Utils\STVElection;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('run:stv-rounds')]
#[Description('Execute the STV algorithm and explain each round')]
class STVRounds extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $election = new STVElection(toElect: 8);
        $election->count();
        $rounds = $election->getRoundSummaries();

        $this->info('Single Transferable Vote by Round');

        if ($rounds === []) {
            $this->warn('No STV ballots were found.');

            return self::SUCCESS;
        }

        foreach ($rounds as $round) {
            $this->newLine();
            $this->comment(sprintf('Round %d', $round['round']));
            $this->line(sprintf('Quota: %.4f | Seats remaining: %d', $round['quota'], $round['seats_remaining']));

            if ($round['restriction_reason'] !== null) {
                $this->warn($round['restriction_reason']);
            }

            $this->table(
                ['Candidate', 'Gender', 'Votes'],
                array_map(static fn (array $tally): array => [
                    $tally['name'],
                    $tally['gender'],
                    $tally['votes'],
                ], $round['tallies'])
            );

            $this->line(sprintf('Action: %s', $round['reason']));
        }

        $this->newLine();
        $this->info('Final STV ranking');
        $this->table(
            ['#', 'Candidate', 'Gender', 'Points', 'Elected', 'Comments'],
            $election->getResults()
        );

        return self::SUCCESS;
    }
}

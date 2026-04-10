<?php

namespace App\Console\Commands;

use App\Utils\IRVElection;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('run:irv')]
#[Description('Execute the IRV algorithm')]
class IRV extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $election = new IRVElection(toElect: 8);
        $election->count();
        $results = $election->getResults();

        $this->info('Instant-Runoff Voting');
        $this->table(
            ['#', 'Candidate', 'Gender', 'Points', 'Elected', 'Comments'],
            $results
        );
    }
}

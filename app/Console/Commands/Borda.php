<?php

namespace App\Console\Commands;

use App\Utils\BordaElection;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('run:borda')]
#[Description('Execute the Borda count algorithm')]
class Borda extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $election = new BordaElection(toElect: 8);
        $election->addBordaPoints();
        $results = $election->getResults();

        $this->table(
            ['#', 'Candidate', 'Gender', 'Points', 'Elected', 'Comments'],
            $results
        );
    }
}

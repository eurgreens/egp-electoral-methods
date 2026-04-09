<?php

namespace App\Console\Commands;

use App\Utils\DowdallElection;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('run:dowdall')]
#[Description('Execute the Dowdall count algorithm')]
class Dowdall extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $election = new DowdallElection(toElect: 8);
        $election->addDowdallPoints();
        $results = $election->getResults();

        $this->table(
            ['#', 'Candidate', 'Gender', 'Points', 'Elected', 'Comments'],
            $results
        );
    }
}

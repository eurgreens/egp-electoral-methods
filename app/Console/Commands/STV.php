<?php

namespace App\Console\Commands;

use App\Utils\STVElection;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('run:stv')]
#[Description('Execute the STV algorithm')]
class STV extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $election = new STVElection(toElect: 8);
        $election->count();
        $results = $election->getResults();

        $this->info('Single Transferable Vote');
        $this->table(
            ['#', 'Candidate', 'Gender', 'Points', 'Elected', 'Comments'],
            $results
        );
    }
}

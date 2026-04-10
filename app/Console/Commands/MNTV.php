<?php

namespace App\Console\Commands;

use App\Utils\MNTVElection;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('run:mntv')]
#[Description('Run the MNTV election')]
class MNTV extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $election = new MNTVElection(toElect: 8);
        $election->count();
        $results = $election->getResults();

        $this->info('MNTV');
        $this->table(
            ['#', 'Candidate', 'Gender', 'Points', 'Elected', 'Comments'],
            $results
        );
    }
}

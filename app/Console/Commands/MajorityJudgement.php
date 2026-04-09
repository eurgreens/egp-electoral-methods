<?php

namespace App\Console\Commands;

use App\Utils\MajorityJudgementElection;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('run:mj')]
#[Description('Execute the Majority Judgement algorithm')]
class MajorityJudgement extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $election = new MajorityJudgementElection(toElect: 8);
        $election->addMajorityJudgementPoints();
        $results = $election->getResults();

        $this->table(
            ['#', 'Candidate', 'Gender', 'Points', 'Elected', 'Comments'],
            $results
        );
    }
}

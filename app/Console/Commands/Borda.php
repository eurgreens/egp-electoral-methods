<?php

namespace App\Console\Commands;

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
        //
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('run:all')]
#[Description('Run all election simulations')]
class RunAll extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Modified Borda Count');
        $this->call('run:borda');
        $this->info('Dowdall Count');
        $this->call('run:dowdall');
        $this->info('Majority Judgement');
        $this->call('run:mj');
        $this->info('MNTV');
        $this->call('run:mntv');
    }
}

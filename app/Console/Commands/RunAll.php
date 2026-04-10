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
        $this->call('run:stv');
        $this->call('run:borda');
        $this->call('run:dowdall');
        $this->call('run:mj');
        $this->call('run:mntv');
    }
}

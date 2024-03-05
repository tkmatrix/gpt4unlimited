<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class random_command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'random:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Random commands';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dd("success");
    }
}

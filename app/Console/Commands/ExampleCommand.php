<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExampleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:example-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Example command for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Example command executed successfully!');
    }
}

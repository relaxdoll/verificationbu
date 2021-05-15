<?php

namespace App\Console\Commands;

use App\Http\Controllers\LineController;
use App\Http\Controllers\LocationController;
use App\RefuelJob;
use Illuminate\Console\Command;

class sendNotUpdateTireReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendNotUpdateTireReport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        RefuelJob::create(['refuel_id' => 0, 'model' => 'pressure']);
        RefuelJob::create(['refuel_id' => 1, 'model' => 'pressure']);
        RefuelJob::create(['refuel_id' => 2, 'model' => 'pressure']);
        RefuelJob::create(['refuel_id' => 3, 'model' => 'pressure']);
        RefuelJob::create(['refuel_id' => 4, 'model' => 'pressure']);
        RefuelJob::create(['refuel_id' => 5, 'model' => 'pressure']);

        $this->info('Create successfully');
    }
}

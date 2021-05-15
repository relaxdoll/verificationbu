<?php

namespace App\Console\Commands;

use App\FastTrackBackground;
use Illuminate\Console\Command;

class ChangeBG extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changeBG';

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
     * @return int
     */
    public function handle()
    {
        $bg = FastTrackBackground::where('isActive', 1)->first();

        $result = FastTrackBackground::find($bg->id + 1);

        if (!is_null($result))
        {
            FastTrackBackground::activateBackground($result->id);
        }else{
            FastTrackBackground::activateBackground(1);
        }

       $this->info('Background changed');
    }
}

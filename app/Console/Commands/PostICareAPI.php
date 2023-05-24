<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PostICareAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:PostICareAPI';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scheduling Post API to I-Care';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public $dateNow;

    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/bangkok');
        $this->dateNow = Carbon::now();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return 0;
    }
}

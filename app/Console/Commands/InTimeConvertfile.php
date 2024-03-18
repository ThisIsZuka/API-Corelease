<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\LocalService\ConvertIMG_File;

class InTimeConvertfile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:convert_img';

    /**
     * The console cron description.
     *
     * @var string
     */
    protected $description = 'cron Convert IMG';

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
        $ConvertIMG_File = new ConvertIMG_File();
        $ConvertIMG_File->ConvertFile();
    }
}

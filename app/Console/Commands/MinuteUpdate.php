<?php

namespace App\Console\Commands;

use App\Models\Submenu;
use Illuminate\Console\Command;
use DB;

class MinuteUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Daily:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Qty Reset';

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
        $submenus = Submenu::whereStatus(1)->get();
        foreach ($submenus as $submenu) 
        {
            if ($submenu->qty_reset == 'daily') {
                $submenu->item_reset_value = 0;
                $submenu->availabel_item = 0;
                $submenu->save();
            }
        }
    }
}
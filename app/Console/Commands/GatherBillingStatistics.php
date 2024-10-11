<?php

namespace App\Console\Commands;

use App\Model\Statistics\Stats;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GatherBillingStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gather:billing-statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gathering Billing Statistics';

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
        $targetDate = Carbon::yesterday()->format('Y-m-d');

        // Call Procedure from Model
        Stats::gatherBilling($targetDate);

    }
}

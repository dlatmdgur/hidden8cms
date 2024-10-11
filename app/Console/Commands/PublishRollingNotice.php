<?php

namespace App\Console\Commands;

use App\Model\CMS\RollingNotice;
use App\Model\Statistics\Stats;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class PublishRollingNotice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:rolling-notice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish rolling notice';

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
        $currDay = Carbon::now()->format('YmdHi');
        $currDateTime = Carbon::now()->format('Y-m-d H:i') . ':00';
        $records = RollingNotice::where('dday', '=', $currDay)->whereColumn('noti_count', '>', 'noti_counted')
            ->where('start_datetime', '<=', $currDateTime)->where('expire_datetime', '>=', $currDateTime)
            ->orderBy('idx', 'desc')->get();
        foreach($records as $idx => $record) {
            if($idx == 0) Redis::publish('notice', $record->message);
            $record->noti_counted++;
            if($record->noti_count > $record->noti_counted) {
                $record->dday = Carbon::createFromFormat('YmdHi', $record->dday)->addMinutes($record->noti_interval)->format('YmdHi');
            }
            $record->save();
        }
    }
}

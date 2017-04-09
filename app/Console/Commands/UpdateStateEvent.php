<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Event;

class UpdateStateEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:updatestate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update state event';

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
    public function handle() {
        // 開催期間になったイベントを開催する
        $today = date('Y-m-d');
        $events_s = Event::where('start', $today)->where('state', Event::YET)->get();
        foreach($events_s as $event) {
            $event->state = Event::OPEN;
            $event->save();
        }

        // 開催期間が終了したイベントを審査中に変更する
        $events_e = Event::where('end', $today)->where('state', Event::OPEN)->get();
        foreach($events_e as $event) {
            $event->state = Event::REVIEW;
            $event->save();
        }

        // 審査が終了しており発表まちを終了に変更する
        $four_day_ago = date('Y-m-d', strtotime("-4day"));
        $events_r = Event::where('end', '<=', $four_day_ago)->where('state', Event::WAIT_CLOSE)->get();
        foreach ($events_r as $event) {
            $event->state = Event::CLOSE;
            $event->save();
        }
    }
}

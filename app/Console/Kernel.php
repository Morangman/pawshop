<?php

declare(strict_types = 1);

namespace App\Console;

use App\Console\Commands\CheckCart;
use App\Console\Commands\CheckEmail;
use App\Console\Commands\ClearDailyStatistics;
use App\Console\Commands\Fedex;
use App\Console\Commands\FedexLabel;
use App\Console\Commands\OrderNormalizeStatus;
use App\Console\Commands\PullData;
use App\Console\Commands\PullPrices;
use App\Console\Commands\PullFailedPrices;
use App\Console\Commands\RecountStatistics;
use App\Console\Commands\Reminder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Reminder::class,
        PullData::class,
        PullPrices::class,
        PullFailedPrices::class,
        CheckCart::class,
        Fedex::class,
        OrderNormalizeStatus::class,
        RecountStatistics::class,
        ClearDailyStatistics::class,
        FedexLabel::class,
        CheckEmail::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command(Reminder::class)->dailyAt('7:00');
        $schedule->command(CheckCart::class)->dailyAt('12:00');
        $schedule->command(Fedex::class)->everyThirtyMinutes();
        $schedule->command(ClearDailyStatistics::class)->daily();
        //$schedule->command(CheckEmail::class)->everyThirtyMinutes();
        $schedule->command(FedexLabel::class)->weeklyOn(1, '12:00');
    }
}

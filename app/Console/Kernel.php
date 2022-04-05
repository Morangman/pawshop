<?php

declare(strict_types = 1);

namespace App\Console;

use App\Console\Commands\CheckCardsAvailable;
use App\Console\Commands\CheckCart;
use App\Console\Commands\CheckEmail;
use App\Console\Commands\CheckIphone;
use App\Console\Commands\ClearDailyStatistics;
use App\Console\Commands\Fedex;
use App\Console\Commands\FedexLabel;
use App\Console\Commands\OrderNormalizeStatus;
use App\Console\Commands\ParseBuybackPrices;
use App\Console\Commands\PullData;
use App\Console\Commands\PullPrices;
use App\Console\Commands\UpdatePrices;
use App\Console\Commands\PullFailedPrices;
use App\Console\Commands\PullGraphicCards;
use App\Console\Commands\RecountStatistics;
use App\Console\Commands\Reminder;
use App\Console\Commands\SendMailToCancelledOrder;
use App\Console\Commands\SetCancelledOrder;
use App\Console\Commands\SetProductsInWarehouse;
use App\Console\Commands\SitemapGenerator;
use App\Console\Commands\UpdateFailedPrices;
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
        UpdatePrices::class,
        UpdateFailedPrices::class,
        PullFailedPrices::class,
        CheckCart::class,
        Fedex::class,
        OrderNormalizeStatus::class,
        RecountStatistics::class,
        ClearDailyStatistics::class,
        FedexLabel::class,
        CheckEmail::class,
        SetProductsInWarehouse::class,
        CheckCardsAvailable::class,
        PullGraphicCards::class,
        SitemapGenerator::class,
        SetCancelledOrder::class,
        SendMailToCancelledOrder::class,
        ParseBuybackPrices::class,
        CheckIphone::class
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
        $schedule->command(CheckEmail::class)->everyThirtyMinutes();
        $schedule->command(FedexLabel::class)->dailyAt('12:00');
        $schedule->command(SetCancelledOrder::class)->dailyAt('12:00');
        // $schedule->command(UpdatePrices::class)->monthly();
        // $schedule->command(UpdateFailedPrices::class)->dailyAt('21:00');
        // $schedule->command(CheckCardsAvailable::class)->everyMinute();
        // $schedule->command(CheckIphone::class)->everyMinute();
        $schedule->command(SitemapGenerator::class)->daily();
        $schedule->command(ParseBuybackPrices::class)->daily();
    }
}

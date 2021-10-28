<?php

declare(strict_types = 1);

namespace App\Jobs;

use App\AllDailyStatistic;
use App\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class ClearDailyStatisticsJob
 *
 * @package App\Jobs
 */
class ClearDailyStatisticsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $dailyViews = DB::table('daily_statistics')
            ->groupBy('category_id')
            ->select([DB::raw("SUM(steps_view_count) as sum"), DB::raw("SUM(steps_coefficient) as coef"), DB::raw("category_id as category_id")])
            ->get();

        foreach ($dailyViews as $view) {
            $data = (array) $view;

            $cat = Category::query()->whereKey((int) $data['category_id'])->first();

            if ($cat) {
                AllDailyStatistic::query()->create([
                    'steps_view_count' => (int) $data['sum'],
                    'steps_coefficient' => (float) $data['coef'],
                    'category_id' => $cat->getKey(),
                    'title' => $cat->getAttribute('name'),
                    'image' => $cat->getAttribute('image'),
                    'price' => (float) $cat->getAttribute('custom_text'),
                    'date' => Carbon::now()->subDays(1),
                ]);
            }
        }

        DB::table('daily_statistics')->truncate();
    }
}

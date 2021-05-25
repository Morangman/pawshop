<?php

declare(strict_types = 1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Spatie\Sitemap\SitemapGenerator;

/**
 * Class SitemapGeneratorJob
 *
 * @package App\Jobs
 */
class SitemapGeneratorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        SitemapGenerator::create('https://rapid-recycle.com')->getSitemap()->writeToFile(public_path('sitemap.xml'));
    }
}

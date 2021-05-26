<?php

declare(strict_types = 1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

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
        SitemapGenerator::create('https://rapid-recycle.com')->hasCrawled(function (Url $url) {
            if ($url->path() === '/') {
                $url->setPriority(1)
                    ->setLastModificationDate(Carbon::now());
            }

            if ($url->path() === '/redirect-facebook' ||
                $url->path() === '/redirect-google' ||
                $url->path() === '/support' ||
                $url->path() === '/web/login' ||
                $url->path() === '/web/password/reset' ||
                $url->path() === '/web/register' ||
                $url->path() === '/account' ||
                $url->path() === '/callback' ||
                $url->path() === '/user_agreement' ||
                $url->path() === '/privacy_policy' ||
                $url->path() === '/terms' ||
                $url->path() === '/law_enforcement' ||
                $url->path() === '/cart'
            ) {
                return;
            }

            return $url;
        })->writeToFile(public_path('sitemap-test.xml'));
    }
}

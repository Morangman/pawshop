<?php

declare(strict_types = 1);

namespace App\Jobs;

use App\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
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
        $now = date('c', time());

        $host = 'https://rapid-recycle.com';

        $header = '<?xml version="1.0" encoding="UTF-8"?>
                <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
                <url>
                    <loc>' . $host . '</loc>
                    <lastmod>' . $now .'</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>1.0</priority>
                </url>';

        $footer = '</urlset>';

        $pages = [
            'support',
            'privacy_policy',
        ];

        foreach ($pages as $page) {
            $header .= '<url>'.
                '<loc>' . $host . '/' . $page . '</loc>' .
                '<lastmod>' . $now .'</lastmod>' .
                '<changefreq>daily</changefreq>' .
                '<priority>0.8</priority>' .
            '</url>';
        }

        $categories = Category::query()->where('is_hidden', false)->toBase()->get();

        foreach ($categories as $category) {
            $subCategory = null;

            if ($category->subcategory_id) {
                $subCategory = $categories->where('id', $category->subcategory_id)->first();
                // $subCategory = Category::query()->toBase()->find($category->subcategory_id);
            }

            if ($subCategory) {
                $header .= '<url>'.
                    '<loc>' . $host . '/sell-' . $subCategory->slug . '/' . $category->slug . '</loc>' .
                    '<lastmod>' . $now .'</lastmod>' .
                    '<changefreq>daily</changefreq>' .
                    '<priority>' . ($category->custom_text ? '0.6' : '0.8') . '</priority>' .
                '</url>';
            } else {
                $header .= '<url>'.
                    '<loc>' . $host . '/sell-' . $category->slug . '</loc>' .
                    '<lastmod>' . $now .'</lastmod>' .
                    '<changefreq>daily</changefreq>' .
                    '<priority>' . ($category->custom_text ? '0.6' : '0.8') . '</priority>' .
                '</url>';
            }
        }

        $header .= $footer;

        file_put_contents(public_path('sitemap.xml'), $header);

        // SitemapGenerator::create('https://rapid-recycle.com')->hasCrawled(function (Url $url) {
        //     if ($url->path() === '') {
        //         $url->setPriority(1)
        //             ->setLastModificationDate(Carbon::now());
        //     }

        //     $slug = str_replace('sell-', '', substr($url->path(), strrpos($url->path(), '/') + 1));

        //     $cat = Category::query()->where('slug', $slug)->first();

        //     if ($cat && $cat->getAttribute('custom_text') !== null) {
        //         $url->setPriority(0.6)
        //             ->setLastModificationDate(Carbon::now());
        //     }

        //     if ($url->path() === '/support' ||
        //         $url->path() === '/account' ||
        //         $url->path() === '/user_agreement' ||
        //         $url->path() === '/privacy_policy' ||
        //         $url->path() === '/terms' ||
        //         $url->path() === '/law_enforcement' ||
        //         $url->path() === '/cart'
        //     ) {
        //         $url->setPriority(0.8)
        //             ->setLastModificationDate(Carbon::now());
        //     }

        //     if ($url->path() === '/web/login' ||
        //         $url->path() === '/web/password/reset' ||
        //         $url->path() === '/web/register'
        //     ) {
        //         $url->setPriority(0.6)
        //             ->setLastModificationDate(Carbon::now());
        //     }

        //     if ($url->path() === '/redirect-facebook' ||
        //         $url->path() === '/redirect-google' ||
        //         $url->path() === '/callback' ||
        //         $url->path() === '/unsubscribe' ||
        //         $url->path() === '/'
        //     ) {
        //         return;
        //     }

        //     return $url;
        // })->writeToFile(public_path('sitemap.xml'));
    }
}

<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Traits;

use App\Setting;
use Butschster\Head\Facades\Meta;
use Butschster\Head\Packages\Entities\OpenGraphPackage;

/**
 * Trait SettingTrait
 *
 * @package App\Http\Controllers\Traits
 */
trait SettingTrait
{
    /**
     * @return \App\Setting
     */
    protected function getSettings(): Setting
    {
        $settings = Setting::latest('updated_at')->first() ?? null;

        $seoTitle = isset($settings) && isset($settings->getAttribute('general_settings')['seo_title'])
            ? $settings->getAttribute('general_settings')['seo_title']
            : '';
        $seoImage = isset($settings) && isset($settings->getAttribute('general_settings')['seo_image'])
            ? $settings->getAttribute('general_settings')['seo_image']
            : '';

        $og = new OpenGraphPackage('home_og');

        $og->setType('article')
            ->setSiteName($seoTitle)
            ->setTitle($seoTitle)
            ->addImage($seoImage);

        $og->toHtml();

        Meta::registerPackage($og);

        Meta::prependTitle($seoTitle)
            ->setKeywords(isset($settings) ? $settings->getAttribute('general_settings')['seo_keywords'] : '')
            ->setDescription($seoTitle);

        return $settings;
    }
}

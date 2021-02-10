<?php

declare(strict_types = 1);

use Illuminate\Database\Seeder;
use App\Setting;

/**
 * Class SettingSeeder
 */
class SettingSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        Setting::query()->create([
            'general_settings' => [
                "seo_meta" => "Seo meta title",
                "seo_image" => "",
                "seo_title" => "Seo title",
                "seo_keywords" => "Seo keywords"
            ],
            'code_insert' => '',
        ]);
    }
}

<?php

declare(strict_types = 1);

use Illuminate\Database\Seeder;
use App\Setting;
use Illuminate\Support\Facades\Config;

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
                "seo_keywords" => "Seo keywords",
                "base_path" => Config::get('parser.base_path'),
                "iphones_point" => Config::get('parser.iphones_point'),
                "samsung_point" => Config::get('parser.samsung_point'),
                "htc_phones" => Config::get('parser.htc_phones'),
                "motorola_phones" => Config::get('parser.motorola_phones'),
                "lg_phones" => Config::get('parser.lg_phones'),
                "onePlus_phones" => Config::get('parser.onePlus_phones'),
                "google_phones" => Config::get('parser.google_phones'),
                "sony_phones" => Config::get('parser.sony_phones'),
                "blackBerry_phones" => Config::get('parser.blackBerry_phones'),
                "huawei_phones" => Config::get('parser.huawei_phones'),
                "kyocera_phones" => Config::get('parser.kyocera_phones'),
                "zte_phones" => Config::get('parser.zte_phones'),
                "xiaomi_phones" => Config::get('parser.xiaomi_phones'),
                "razer_phones" => Config::get('parser.razer_phones'),
                "nokia_phones" => Config::get('parser.nokia_phones'),
                "asus_phones" => Config::get('parser.asus_phones'),
                "ipads_point" => Config::get('parser.ipads_point'),
                "samsung_tablets_point" => Config::get('parser.samsung_tablets_point'),
                "microsoft_tablets_point" => Config::get('parser.microsoft_tablets_point'),
                "iPods_point" => Config::get('parser.iPods_point'),
                "gopro_point" => Config::get('parser.gopro_point'),
                "xbox_point" => Config::get('parser.xbox_point'),
                "playstation_point" => Config::get('parser.playstation_point'),
                "switch_point" => Config::get('parser.switch_point'),
                "ds_point" => Config::get('parser.ds_point'),
                "appleWatch_point" => Config::get('parser.appleWatch_point'),
            ],
            'terms' => '',
            'code_insert' => '',
        ]);
    }
}

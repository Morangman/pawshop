<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Setting;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\StoreRequest;
use App\Http\Requests\Admin\Setting\UpdateRequest;
use App\Step;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

/**
 * Class SettingController
 *
 * @package App\Http\Controllers\Admin
 */
class SettingController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function index(): ViewContract
    {
        return View::make('admin.setting.index', [
            'settings' => Setting::latest('updated_at')->first() ?? (object)[]
        ]);
    }

    /**
     * @param \App\Http\Requests\Admin\Setting\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $settingData = $request->except(
                [
                    'code_insert',
                ]
            ) + [
                'code_insert' => $request->get('code_insert') ?? '',
            ];

        $setting = Setting::create($settingData);

        $this->handleDocuments($request, $setting);

        Session::flash(
            'success',
            Lang::get('admin/setting.messages.create')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Http\Requests\Admin\Setting\UpdateRequest $request
     * @param \App\Setting $setting
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function update(UpdateRequest $request, Setting $setting): JsonResponse
    {
        if((bool) $request->get('is_seo_image_deleted') === true){
            if($firstMedia = $setting->getMedia(Setting::MEDIA_COLLECTION_SETTING)){
                $setting->deleteMedia($firstMedia->first());
            }
        }

        if (isset($request->get('general_settings')['band_price'])) {
            $bandPrice = $request->get('general_settings')['band_price'];

            $stepsIds = Step::query()->where('name_id', '=', 6)->pluck('id')->toArray();

            DB::table('premium_price')->whereIn('step_id', $stepsIds)->update(['price_plus' => $bandPrice]);
        }
        
        $settingData = $request->except(
                [
                    'code_insert',
                    'law_enforcement',
                    'privacy_policy',
                    'terms',
                    'user_agreement',
                ]
            ) + [
                'code_insert' => $request->get('code_insert') ?? '',
                'law_enforcement' => $request->get('law_enforcement') ?? '',
                'privacy_policy' => $request->get('privacy_policy') ?? '',
                'terms' => $request->get('terms') ?? '',
                'user_agreement' => $request->get('terms') ?? '',
            ];

        $setting->update($settingData);

        $this->handleDocuments($request, $setting);

        Session::flash(
            'success',
            Lang::get('admin/setting.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Setting $setting
     *
     * @return void
     *
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    protected function handleDocuments(Request $request, Setting $setting): void
    {
        if((bool) $request->get('is_seo_image_deleted') === true){
            if($firstMedia = $setting->getMedia(Setting::MEDIA_COLLECTION_SETTING)){
                $setting->deleteMedia($firstMedia->first());
            }
        } else {
            $seoImageUrl = "";

            if (isset($request->file('general_settings')['seo_image']) && $settingSeoImage = $request->file('general_settings')['seo_image']) {

                if($firstMedia = $setting->getFirstMediaUrl(Setting::MEDIA_COLLECTION_SETTING)){
                    $setting->deleteMedia($firstMedia);
                }

                $media = $setting->addMedia($settingSeoImage)
                    ->toMediaCollection(Setting::MEDIA_COLLECTION_SETTING);

                $seoImageUrl = $media->getFullUrl();
            } else {
                $seoImageUrl = $setting->getFirstMediaUrl(Setting::MEDIA_COLLECTION_SETTING);
            }

            $setting->update([
                'code_insert' => $request->get('code_insert') ?? '',
                'general_settings' => [
                    'email' => $request->get('general_settings')['email'] ?? null,
                    'band_price' => isset($request->get('general_settings')['band_price']) ? $request->get('general_settings')['band_price'] : null,
                    'contact_email' => $request->get('general_settings')['contact_email'] ?? null,
                    'phone' => $request->get('general_settings')['phone'] ?? null,
                    'seo_meta' => $request->get('general_settings')['seo_meta'] ?? null,
                    'seo_title' => $request->get('general_settings')['seo_title'] ?? null,
                    'seo_keywords' => $request->get('general_settings')['seo_keywords'] ?? null,
                    'iframe_map' => $request->get('general_settings')['iframe_map'] ?? null,
                    'seo_image' => $seoImageUrl,
                    "base_path" => $request->get('general_settings')['base_path'] ?? null,
                    "iphones_point" => $request->get('general_settings')['iphones_point'] ?? null,
                    "samsung_point" => $request->get('general_settings')['samsung_point'] ?? null,
                    "htc_phones" => $request->get('general_settings')['htc_phones'] ?? null,
                    "motorola_phones" => $request->get('general_settings')['motorola_phones'] ?? null,
                    "lg_phones" => $request->get('general_settings')['lg_phones'] ?? null,
                    "onePlus_phones" => $request->get('general_settings')['onePlus_phones'] ?? null,
                    "google_phones" => $request->get('general_settings')['google_phones'] ?? null,
                    "sony_phones" => $request->get('general_settings')['sony_phones'] ?? null,
                    "blackBerry_phones" => $request->get('general_settings')['blackBerry_phones'] ?? null,
                    "huawei_phones" => $request->get('general_settings')['huawei_phones'] ?? null,
                    "kyocera_phones" => $request->get('general_settings')['kyocera_phones'] ?? null,
                    "zte_phones" => $request->get('general_settings')['zte_phones'] ?? null,
                    "xiaomi_phones" => $request->get('general_settings')['xiaomi_phones'] ?? null,
                    "razer_phones" => $request->get('general_settings')['razer_phones'] ?? null,
                    "nokia_phones" => $request->get('general_settings')['nokia_phones'] ?? null,
                    "asus_phones" => $request->get('general_settings')['asus_phones'] ?? null,
                    "ipads_point" => $request->get('general_settings')['ipads_point'] ?? null,
                    "samsung_tablets_point" => $request->get('general_settings')['samsung_tablets_point'] ?? null,
                    "microsoft_tablets_point" => $request->get('general_settings')['microsoft_tablets_point'] ?? null,
                    "iPods_point" => $request->get('general_settings')['iPods_point'] ?? null,
                    "gopro_point" => $request->get('general_settings')['gopro_point'] ?? null,
                    "xbox_point" => $request->get('general_settings')['xbox_point'] ?? null,
                    "playstation_point" => $request->get('general_settings')['playstation_point'] ?? null,
                    "switch_point" => $request->get('general_settings')['switch_point'] ?? null,
                    "ds_point" => $request->get('general_settings')['ds_point'] ?? null,
                    "appleWatch_point" => $request->get('general_settings')['appleWatch_point'] ?? null,
                ]
            ]);
        }
    }
}

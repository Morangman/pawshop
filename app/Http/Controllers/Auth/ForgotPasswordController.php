<?php

namespace App\Http\Controllers\Auth;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Setting;
use Butschster\Head\Facades\Meta;
use Butschster\Head\Packages\Entities\OpenGraphPackage;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Redirect;
use View;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function showLinkRequestForm(): ViewContract
    {
        $settings = Setting::latest('updated_at')->first() ?? null;

        $categories = Category::query()->where('is_hidden', false)->whereNotNull('custom_text')->get();

        $seoTitle = isset($settings) && isset($settings->getAttribute('general_settings')['seo_title'])
            ? $settings->getAttribute('general_settings')['seo_title']
            : '';
        $seoImage = isset($settings) && isset($settings->getAttribute('general_settings')['seo_image'])
            ? $settings->getAttribute('general_settings')['seo_image']
            : '';

        $og = new OpenGraphPackage('home_og');

        $og->setType('OG META TAGS')
            ->setSiteName($seoTitle)
            ->setTitle($seoTitle)
            ->addImage($seoImage);

        $og->toHtml();

        Meta::registerPackage($og);

        Meta::prependTitle($seoTitle)
            ->setKeywords(isset($settings) ? $settings->getAttribute('general_settings')['seo_keywords'] : '')
            ->setDescription($seoTitle);

        return View::make('auth.forgot', [
            'settings' => $settings ?? [],
            'categories' => $categories
        ]);
    }

    /**
     * @param \App\Http\Requests\Auth\ForgotPasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $settings = Setting::latest('updated_at')->first() ?? null;

        $categories = Category::query()->where('is_hidden', false)->whereNotNull('custom_text')->get();

        $seoTitle = isset($settings) && isset($settings->getAttribute('general_settings')['seo_title'])
            ? $settings->getAttribute('general_settings')['seo_title']
            : '';
        $seoImage = isset($settings) && isset($settings->getAttribute('general_settings')['seo_image'])
            ? $settings->getAttribute('general_settings')['seo_image']
            : '';

        $og = new OpenGraphPackage('home_og');

        $og->setType('OG META TAGS')
            ->setSiteName($seoTitle)
            ->setTitle($seoTitle)
            ->addImage($seoImage);

        $og->toHtml();

        Meta::registerPackage($og);

        Meta::prependTitle($seoTitle)
            ->setKeywords(isset($settings) ? $settings->getAttribute('general_settings')['seo_keywords'] : '')
            ->setDescription($seoTitle);

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response === Password::RESET_LINK_SENT
            ? View::make('auth.reset_success', [
                'settings' => $settings ?? [],
                'categories' => $categories
            ])
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * Show success page after reset password email sending.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showSuccessPage(): ViewContract
    {
        return View::make('auth.reset_success');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SettingTrait;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Redirect;
use View;

class ForgotPasswordController extends Controller
{
    use SettingTrait;

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
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        return View::make('auth.forgot', [
            'settings' => $this->getSettings() ?? [],
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
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response === Password::RESET_LINK_SENT
            ? View::make('auth.reset_success', [
                'settings' => $this->getSettings() ?? [],
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

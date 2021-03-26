<?php

namespace App\Http\Controllers\Auth;

use App\Category;
use App\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SettingTrait;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\RedirectResponse;
use Redirect;
use View;
use URL;
use Session;
use Lang;
use stdClass;

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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        // $response = $this->broker()->sendResetLink(
        //     $request->only('email')
        // );

        $user = User::query()->where('email', $request->only('email'))->first();

        if (!$user) {
            Session::flash('error', Lang::get('auth.failed'));

            return new RedirectResponse(URL::route('web.password.request'), 302);
        }

        $token = Password::getRepository()->create($user);

        Mail::to($user->getAttribute('email'))
            ->send(new ResetPasswordMail(
                $token,
                $user->getAttribute('email')
            ));

        if (Mail::failures()) {
            Session::flash('error', Lang::get('auth.failed'));

            return new RedirectResponse(URL::route('web.password.request'), 302);
        }

        return View::make('auth.reset_success', [
            'settings' => $this->getSettings() ?? [],
            'categories' => $categories,
            'category' => new stdClass(),
            'steps' => [],
            'relatedCategories' => $categories,
            'faqs' => new stdClass(),
        ]);
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

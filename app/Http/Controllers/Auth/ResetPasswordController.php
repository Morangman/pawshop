<?php

namespace App\Http\Controllers\Auth;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SettingTrait;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use View;

class ResetPasswordController extends Controller
{
    use SettingTrait;

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @param \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param string $password
     *
     * @return void
     */
    protected function resetPassword(CanResetPassword $user, string $password): void
    {
        $user->setAttribute('password', $password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        Event::dispatch(new PasswordReset($user));

        $this->guard()->login($user);
    }

    /**
     * Reset the given user's password.
     *
     * @param \App\Http\Requests\Auth\ResetPasswordRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(ResetPasswordRequest $request)
    {
        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $token
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showResetForm(Request $request, string $token): ViewContract
    {
        $categories = Category::query()->where('is_hidden', false)->whereNotNull('custom_text')->get();

        return View::make('auth.forgot_new_pass', [
            'token' => $token,
            'email' => $request->get('email'),
            'settings' => $this->getSettings() ?? [],
            'categories' => $categories,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SettingTrait;
use App\Http\Requests\Auth\RegisterRequest;
use App\Notifications\RegisterConfirmationNotification;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Lang;
use Notification;
use Session;
use URL;
use View;

class RegisterController extends Controller
{
    use SettingTrait;

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        /** @var \App\User $user */
        $user = \App\User::query()->create($request->all());

        $user->attachRole('user');

        Notification::send(
            $user,
            new RegisterConfirmationNotification($user->getAttribute('register_code'))
        );

        Session::flash('status', Lang::get('auth.register.verify.title'));

        return new RedirectResponse(URL::route('web.login.show'), 302);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function showRegistrationForm(): ViewContract
    {
        $categories = Category::query()->where('is_hidden', false)->whereNull('custom_text')->get();

        return View::make('auth.registration', [
            'settings' => $this->getSettings() ?? [],
            'categories' => $categories
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $code
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function verifyEmail(Request $request, string $code): RedirectResponse
    {
        $user = User::query()
            ->where('email', $request->get('email'))
            ->where('register_code', $code)
            ->first();

        if ($user) {
            User::query()->whereKey($user->getKey())->update([
                'is_active' => true,
                'register_code' => null,
                'email_verified_at' => Carbon::now(),
            ]);

            Auth::login($user);
        } else {
            Session::flash('error', Lang::get('auth.register.verify.error'));

            return new RedirectResponse(URL::route('auth.register'), 302);
        }

        return new RedirectResponse(URL::route('home'), 302);
    }
}

<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SettingTrait;
use App\Order;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Lang;
use Laravel\Socialite\Facades\Socialite;
use View;
use stdClass;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    use SettingTrait;

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected function authenticated(Request $request, $user)
    {
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        if ( $user->hasRole('admin') || $user->hasRole('manager')) {// do your magic here
            return redirect()->route('admin.order.index');
        }

        return \Illuminate\Support\Facades\View::make('account', [
            'settings' => $this->getSettings() ?? [],
            'categories' => $categories,
            'category' => new stdClass(),
            'steps' => [],
            'user' => \Illuminate\Support\Facades\Auth::user(),
            'relatedCategories' => $categories,
            'faqs' => new stdClass(),
            'states' => Lang::get('states'),
            'statuses' => Lang::get('admin/order.order_statuses'),
            'orders' => Order::query()->where('user_id', Auth::id())->get() ?? [],
            'tab' => ''
        ]);
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogleProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToFacebookProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function handleProviderGoogleCallback(): ViewContract
    {
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return View::make('auth.login', [
                'settings' => $this->getSettings() ?? [],
                'categories' => $categories
            ]);
        }

        // check if they're an existing user
        $existingUser = User::query()->where('email', $user->email)->first();

        if($existingUser){
            Auth::login($existingUser);
        } else {
            $createdUser = User::query()->create([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => '+18000000000',
                'email_verified_at' => Carbon::now(),
                'is_active' => 1,
                'password' => 'password',
            ]);

            $createdUser->attachRole('user');

            Mail::to($createdUser->getAttribute('email'))
                ->send(new VerificationMail(
                    $createdUser->toArray(),
                ));

            Auth::login($createdUser);
        }

        return \Illuminate\Support\Facades\View::make('account', [
            'settings' => $this->getSettings() ?? [],
            'categories' => $categories,
            'category' => new stdClass(),
            'steps' => [],
            'user' => \Illuminate\Support\Facades\Auth::user(),
            'relatedCategories' => $categories,
            'faqs' => new stdClass(),
            'states' => Lang::get('states'),
            'statuses' => Lang::get('admin/order.order_statuses'),
            'orders' => Order::query()->where('user_id', Auth::id())->get() ?? [],
            'tab' => ''
        ]);
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function handleProviderFacebookCallback(): ViewContract
    {
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        try {
            $user = Socialite::driver('facebook')->user();
        } catch (\Exception $e) {
            return View::make('auth.login', [
                'settings' => $this->getSettings() ?? [],
                'categories' => $categories
            ]);
        }

        // check if they're an existing user
        $existingUser = User::query()->where('email', $user->email)->first();

        if($existingUser){
            Auth::login($existingUser);
        } else {
            $createdUser = User::query()->create([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => '+18000000000',
                'email_verified_at' => Carbon::now(),
                'is_active' => 1,
                'password' => 'password',
            ]);

            $createdUser->attachRole('user');

            Mail::to($createdUser->getAttribute('email'))
                ->send(new VerificationMail(
                    $createdUser->toArray(),
                ));

            Auth::login($createdUser);
        }

        return \Illuminate\Support\Facades\View::make('account', [
            'settings' => $this->getSettings() ?? [],
            'categories' => $categories,
            'category' => new stdClass(),
            'steps' => [],
            'user' => \Illuminate\Support\Facades\Auth::user(),
            'relatedCategories' => $categories,
            'faqs' => new stdClass(),
            'states' => Lang::get('states'),
            'statuses' => Lang::get('admin/order.order_statuses'),
            'orders' => Order::query()->where('user_id', Auth::id())->get() ?? [],
            'tab' => ''
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function showLoginForm(): ViewContract
    {
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        return View::make('auth.login', [
            'settings' => $this->getSettings() ?? [],
            'categories' => $categories
        ]);
    }

    /**
     * @return void
     *
     * @throws \Exception
     */
    protected function sendFailedLoginResponse(): void
    {
        throw ValidationException::withMessages([
            $this->username() => Lang::get('auth.failed'),
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function credentials(Request $request): array
    {
        return $request->only($this->username(), 'password') + ['is_active' => 1];
    }
}

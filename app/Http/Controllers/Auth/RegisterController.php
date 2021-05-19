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
use App\Mail\VerificationMail;
use App\Order;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Lang;
use Notification;
use Session;
use URL;
use View;
use stdClass;

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

    use RegistersUsers, SettingTrait;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function register(RegisterRequest $request): ViewContract
    {
        /** @var \App\User $user */
        $user = \App\User::query()->create($request->all());

        $user->attachRole('user');

        User::query()->whereKey($user->getKey())->update([
            'is_active' => true,
            'register_code' => null,
            'email_verified_at' => Carbon::now(),
        ]);

        // Mail::to($user->getAttribute('email'))
        //     ->send(new VerificationMail(
        //         $user->toArray(),
        //     ));

        Auth::login($user);

        //Session::flash('status', Lang::get('auth.register.verify.title'));

        //return new RedirectResponse(URL::route('web.login.show'), 302);

        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

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
            'orders' => Order::query()->where('user_id', Auth::id())->with('orderStatus')->get() ?? [],
            'tab' => ''
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function showRegistrationForm(): ViewContract
    {
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

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

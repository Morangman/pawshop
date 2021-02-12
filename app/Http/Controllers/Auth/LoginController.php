<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Category;
use App\Http\Controllers\Controller;
use App\Setting;
use App\User;
use Butschster\Head\Facades\Meta;
use Butschster\Head\Packages\Entities\OpenGraphPackage;
use Carbon\Carbon;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Lang;
use Auth;
use Laravel\Socialite\Facades\Socialite;
use View;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{
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

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
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

        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return View::make('auth.login', [
                'settings' => $settings ?? [],
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
                'is_active' => true,
                'password' => 'password',
            ]);

            Auth::login($createdUser);
        }

        return redirect()->to('/');
    }


    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function showLoginForm(): ViewContract
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

        return View::make('auth.login', [
            'settings' => $settings ?? [],
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

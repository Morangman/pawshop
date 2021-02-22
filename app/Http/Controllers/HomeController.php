<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Category;
use App\Comment;
use App\Faq;
use App\Http\Controllers\Traits\SettingTrait;
use App\Http\Requests\Admin\Order\StoreRequest;
use App\Http\Requests\Client\Callback\StoreRequest as CallbackRequest;
use App\Http\Requests\Client\Order\StoreRequest as ClientOrderStoreRequest;
use App\Http\Requests\Client\Account\StoreRequest as UpdateAccountInfoRequest;
use App\Notifications\CommentNotification;
use App\Notifications\ContactNotification;
use App\Order;
use App\Setting;
use App\Tip;
use App\User;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\View;
use Lang;
use Session;
use stdClass;
use App\Services\FedexService;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    use SettingTrait;

    /**
     * @return \Illuminate\Contracts\View\View
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function index(): ViewContract
    {
//        $fedexService = new FedexService();
//        $fedexService->ship();

        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        return View::make('home', [
            'settings' => $this->getSettings() ?? [],
            'categories' => $categories,
            'category' => new stdClass(),
            'steps' => [],
            'relatedCategories' => $categories,
            'faqs' => new stdClass(),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function comments(): ViewContract
    {
        return View::make('comments', [
            'categories' => Category::query()->where('is_hidden', false)->get() ?? [],
            'settings' => Setting::latest('updated_at')->first() ?? null,
            'comments' => Comment::query()->where('is_hidden', false)->paginate(5) ?? [],
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function cart(): ViewContract
    {
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        return View::make('cart', [
            'settings' => $this->getSettings() ?? [],
            'categories' => $categories,
            'category' => new stdClass(),
            'steps' => [],
            'relatedCategories' => $categories,
            'faqs' => new stdClass(),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function support(): ViewContract
    {
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        return View::make('support', [
            'settings' => $this->getSettings() ?? [],
            'categories' => $categories,
            'category' => new stdClass(),
            'steps' => [],
            'relatedCategories' => $categories,
            'faqs' => new stdClass(),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function checkout(): ViewContract
    {
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        return View::make('checkout', [
            'settings' => $this->getSettings() ?? [],
            'categories' => $categories,
            'category' => new stdClass(),
            'steps' => [],
            'relatedCategories' => $categories,
            'faqs' => new stdClass(),
            'user' => Auth::user() ?? new stdClass(),
            'states' => Lang::get('states'),
        ]);
    }

    /**
     * @param \App\Http\Requests\Admin\Order\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeOrder(StoreRequest $request): JsonResponse
    {
        $orderData = array_merge($request->all(), ['ip_address' => $request->ip()]);

        Order::create($orderData);

        return $this->json()->noContent();
    }

    /**
     * @param \App\Http\Requests\Client\Callback\StoreRequest $request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function callback(CallbackRequest $request): ViewContract
    {
        Notification::send(
            User::query()->scopes(['notifiableUsers'])->get(),
            new ContactNotification($request)
        );

        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        return View::make('support', [
            'settings' => $this->getSettings() ?? [],
            'categories' => $categories,
            'category' => new stdClass(),
            'steps' => [],
            'relatedCategories' => $categories,
            'faqs' => new stdClass(),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function account(): ViewContract
    {
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        return View::make('account', [
            'settings' => $this->getSettings() ?? [],
            'categories' => $categories,
            'category' => new stdClass(),
            'steps' => [],
            'user' => Auth::user(),
            'relatedCategories' => $categories,
            'faqs' => new stdClass(),
        ]);
    }

    /**
     * @param \App\Http\Requests\Client\Account\StoreRequest $request
     * @param \App\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAccountInfo(UpdateAccountInfoRequest $request, User $user): JsonResponse
    {
        $user->update($request->only(['name', 'phone', 'email', 'addresses']));

        return $this->json()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function headerSearchDevice(Request $request): JsonResponse
    {
        $categories = Category::query()
            ->when(
                $request->get('name'),
                function ($query, $search) {
                    $keyword = "%{$search}%";

                    $query->where('name', 'like', $keyword);
                }
            )
            ->where('is_hidden', false)
            ->whereNotNull('custom_text')
            ->take(7)
            ->get();

        return $this->json()->ok($categories);
    }

    /**
     * @param \App\Category $category
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function getByCategory(Category $category): ViewContract
    {
        $relatedCategories = Category::query()
            ->where('subcategory_id', '=', $category->getKey())
            ->get();

        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        $faqs = Faq::query()->whereKey($category->getAttribute('faq_id'))->first();

        if ($steps = $category->steps()->get()->toArray()) {
            foreach ($steps as $key => $step) {
                if (isset($step['tip_id'])) {
                    $steps[$key]['tip'] = Tip::query()->whereKey($step['tip_id'])->first()->toArray() ?? [];
                }
            }
        }

        return View::make('home', [
            'category' => $category,
            'steps' => $steps ?? [],
            'categories' => $categories,
            'settings' => $this->getSettings() ?? [],
            'relatedCategories' => $relatedCategories,
            'faqs' => $faqs ?? new stdClass(),
        ]);
    }

    /**
     * @param \App\Http\Requests\Admin\Comment\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addComment(\App\Http\Requests\Admin\Comment\StoreRequest $request): JsonResponse
    {
        $comment = Comment::create(array_merge($request->all(), ['is_hidden' => true]));

        Notification::send(
            User::query()->scopes(['notifiableUsers'])->get(),
            new CommentNotification($comment->getKey())
        );

        return $this->json()->noContent();
    }
}

<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Category;
use App\Comment;
use App\Http\Requests\Admin\Order\StoreRequest;
use App\Http\Requests\Client\Order\StoreRequest as ClientOrderStoreRequest;
use App\Notifications\CommentNotification;
use App\Order;
use App\Setting;
use App\User;
use Butschster\Head\Packages\Entities\OpenGraphPackage;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\View;
use Butschster\Head\Facades\Meta;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function index(): ViewContract
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

        return View::make('home', [
            'settings' => $settings ?? [],
            'categories' => $categories
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
    public function guarantee(): ViewContract
    {
        return View::make('guarantee', [
            'categories' => Category::query()->where('is_hidden', false)->get() ?? [],
            'settings' => Setting::latest('updated_at')->first() ?? null,
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
     * @param \App\Http\Requests\Client\Order\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function callMe(ClientOrderStoreRequest $request): JsonResponse
    {
        $orderData = $request->except(
                [
                    'ordered_product',
                ]
            ) + [
                'ordered_product' => $request->get('ordered_product') ?? [],
            ];

        Order::create(array_merge($orderData, ['ip_address' => $request->ip()]));

        return $this->json()->noContent();
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

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function accessories(): ViewContract
    {
        return View::make(
            'accessories',
            [
                'categories' => Category::query()
                        ->where('is_hidden', false)
                        ->where('subcategory_id', null)
                        ->get() ?? [],
                'settings' => Setting::latest('updated_at')->first() ?? null
            ]
        );
    }
}

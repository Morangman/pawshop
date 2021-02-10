<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Category;
use App\Comment;
use App\Http\Requests\Admin\Order\StoreRequest;
use App\Http\Requests\Client\Order\StoreRequest as ClientOrderStoreRequest;
use App\Notifications\CommentNotification;
use App\Order;
use App\Product;
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

        $productByCategory = [];

        $products = Product::query()->where('is_hidden', false)->get() ?? [];

        $categories = Category::query()->where('is_hidden', false)->get() ?? [];

        foreach ($products as $product) {
            $productCategory = $product->category()->first();
            if ($productCategory->getAttribute('name') !== Category::ACCESSORIES) {
                $productByCategory[$productCategory->getAttribute('name')][] = [
                    'id' => $product->getKey(),
                    'title' => $product->getAttribute('title'),
                    'image' => $product->getAttribute('image'),
                    'price' => $product->getAttribute('price'),
                    'old_price' => $product->getAttribute('old_price'),
                    'general_info' => $product->getAttribute('general_info'),
                    'variations' => $product->getAttribute('variations'),
                ];
            }
        }

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
            'products' => $productByCategory,
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
     * @param \App\Product $product
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function product(Product $product): ViewContract
    {
        $og = new OpenGraphPackage('product_og');

        $og->setType('OG META TAGS')
            ->setTitle($product->getAttribute('seo')['title'] ?? '')
            ->addImage($product->getAttribute('seo')['image'] ?? '');

        $og->toHtml();

        Meta::registerPackage($og);

        Meta::prependTitle($product->getAttribute('seo')['title'] ?? '')
            ->setKeywords($product->getAttribute('seo')['keywords'] ?? '')
            ->setDescription($product->getAttribute('seo')['meta'] ?? '');

        $ids = Arr::pluck($product->getAttribute('recommended_products'), 'id');

        return View::make(
            'product',
            [
                'product' => $product,
                'categories' => Category::query()->where('is_hidden', false)->get() ?? [],
                'settings' => Setting::latest('updated_at')->first() ?? null,
                'recommended' => Product::query()->whereIn('id', $ids)->get(),
            ]
        );
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function accessories(): ViewContract
    {
        $productByCategory = [];

        $accessories = Product::query()->where('is_hidden', false)->get() ?? [];

        foreach ($accessories as $product) {
            $productCategory = $product->category()->first();
            if ($productCategory->getAttribute('name') === Category::ACCESSORIES) {
                $productByCategory[$productCategory->getAttribute('name')][] = [
                    'id' => $product->getKey(),
                    'title' => $product->getAttribute('title'),
                    'image' => $product->getAttribute('image'),
                    'price' => $product->getAttribute('price'),
                    'old_price' => $product->getAttribute('old_price'),
                    'general_info' => $product->getAttribute('general_info'),
                    'variations' => $product->getAttribute('variations'),
                ];
            }
        }

        return View::make(
            'accessories',
            [
                'products' => $productByCategory,
                'categories' => Category::query()->where('is_hidden', false)->get() ?? [],
                'settings' => Setting::latest('updated_at')->first() ?? null
            ]
        );
    }
}

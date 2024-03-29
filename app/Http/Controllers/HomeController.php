<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Callback;
use App\Cart;
use App\Category;
use App\Comment;
use App\Faq;
use App\Http\Controllers\Traits\SettingTrait;
use App\Http\Controllers\Traits\TelegramTrait;
use App\Http\Requests\Admin\Order\UpdateRequest;
use App\Http\Requests\Client\Account\StoreRequest as UpdateAccountInfoRequest;
use App\Http\Requests\Client\Callback\StoreRequest as CallbackRequest;
use App\Http\Requests\Client\Order\StoreRequest as ClientOrderStoreRequest;
use App\Jobs\OrderConfirmationMailJob;
use App\Jobs\SendToMailLabelJob;
use App\Notifications\CommentNotification;
use App\Notifications\ContactNotification;
use App\Order;
use App\Price;
use App\Services\FedexService;
use App\Setting;
use App\Step;
use App\StepName;
use App\Tip;
use App\User;
use http\Client\Response;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Mail\OrderConfirmationMail;
use App\Message;
use App\Notifications\OrderConfirmNotification;
use App\Notifications\OrderDeclineNotification;
use App\Notifications\OrderRestoreNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Lang;
use Session;
use stdClass;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    use SettingTrait;
    use TelegramTrait;

    /**
     * @return \Illuminate\Contracts\View\View
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function index(): ViewContract
    {
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
            'isMainPage' => true,
            'breadcrumbs' => [],
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
     * @param string $email
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function unsubscribe(string $email): ViewContract
    {
        User::query()->where('email', '=', $email)->update(['mail_subscription' => 0]);

        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        return View::make('unsubscribe', [
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
    public function terms(): ViewContract
    {
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        return View::make('terms', [
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
    public function userAgreement(): ViewContract
    {
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        return View::make('user_agreement', [
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
    public function privacyPolicy(): ViewContract
    {
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        return View::make('privacy_policy', [
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
    public function lawEnforcement(): ViewContract
    {
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        return View::make('law_enforcement', [
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
     * @param \App\Http\Requests\Client\Order\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeOrder(ClientOrderStoreRequest $request): JsonResponse
    {
        $orderData = array_merge($request->all(), [
            'uuid' => uniqid(),
            'ip_address' => $request->ip(),
            'user_email' => $request->get('user_email') ?? Auth::user()->getAttribute('email')
        ]);

        if(Auth::check()) {
            if (Auth::user()->getAttribute('addresses') === null || Auth::user()->getAttribute('addresses') === []) {
                User::query()->whereKey(Auth::id())->update(['addresses' => json_encode([$orderData['address']])]);
            }
    
            if (isset($orderData['address']['phone'])) {
                User::query()->whereKey(Auth::id())->update(['phone' => $orderData['address']['phone']]);
            }
            
            if (Cart::query()->where('user_id', '=', Auth::id())->exists()) {
                Cart::query()->where('user_id', '=', Auth::id())->delete();
            }
        }

        $insuranceSumm = 0;
        $totalSumm = 0;
        $expShipping = 20;

        if (isset($orderData['insurance'])) {
            foreach($orderData['orders']['order'] as $order) {
                $totalSumm += (float) $order['total'];
            }

            if (isset($orderData['exp_service'])) {
                $totalSumm -= $expShipping;
            }

            $insuranceSumm = ($totalSumm * 1)/100;
        }

        $order = Order::create($orderData);

        try {
            $this->sendOffer(
                'New order',
                $order->getKey(),
                route('admin.order.edit', ['order' => $order->getKey()]),
                $orderData['orders']['order'][0]['device']['image'],
                $orderData['orders']['order'][0]['device']['name'],
                round((float) $order->getAttribute('total_summ'), 2),
            );
        } catch (\Exception $e) {
            Log::info('Telegram error ' . $e);
        }

        foreach($order->getAttribute('orders')['order'] as $item) {
            for ($i = 1; $i <= (int) $item['ctn']; $i++) {
                DB::table('order_device')->insert([
                    'order_id' => $order->getKey(),
                    'category_id' => $item['device']['id'],
                ]);
            }
        }

        OrderConfirmationMailJob::dispatch([
            'user_name' => Auth::user()->getAttribute('name'),
            'order' => $order->toArray(),
            'insurance_summ' => $insuranceSumm,
        ]);

        return $this->json()->ok(['order_uuid' => $order->getAttribute('uuid')]);
    }

    /**
     * @param \App\Http\Requests\Client\Callback\StoreRequest $request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function callback(CallbackRequest $request): ViewContract
    {
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $remoteip = $_SERVER['REMOTE_ADDR'];
        
        $data = [
            'secret' => config('services.recaptcha.secretkey'),
            'response' => $request->get('recaptcha'),
            'remoteip' => $remoteip
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $resultJson = json_decode($result);

        if ($resultJson->success != true) {
            return View::make('support', [
                'settings' => $this->getSettings() ?? [],
                'categories' => $categories,
                'category' => new stdClass(),
                'steps' => [],
                'relatedCategories' => $categories,
                'faqs' => new stdClass(),
            ]);
        }

        if ($resultJson->score >= 0.3) {
            if (Callback::query()->where('email', '=', $request->get('email'))->exists()) {
                $callback = Callback::query()->where('email', '=', $request->get('email'))->first();

                Message::query()->create(
                    array_merge(
                        $request->all(),
                        [
                            'sender' => Callback::SENDER_FROM,
                            'chat_id' => $callback->getKey()
                        ]
                    )
                );
            } else {
                $callback = Callback::query()->create(array_merge($request->all(), ['sender' => Callback::SENDER_FROM]));

                Message::query()->create(
                    array_merge(
                        $request->all(),
                        [
                            'sender' => Callback::SENDER_FROM,
                            'chat_id' => $callback->getKey()
                        ]
                    )
                );
            }

            Notification::send(
                 User::query()->scopes(['notifiableUsers'])->get(),
                 new ContactNotification($callback->getKey())
            );

            try {
                $this->sendMessage($request->has('text') ? $request->get('text') : 'Callback notification', route('admin.callback.edit', ['callback' => $callback->getKey()]));
            } catch (\Exception $e) {
                Log::info('Telegram error ' . $e);
            }
    
            return View::make('support', [
                'settings' => $this->getSettings() ?? [],
                'categories' => $categories,
                'category' => new stdClass(),
                'steps' => [],
                'relatedCategories' => $categories,
                'faqs' => new stdClass(),
            ]);
        } else {
            return View::make('support', [
                'settings' => $this->getSettings() ?? [],
                'categories' => $categories,
                'category' => new stdClass(),
                'steps' => [],
                'relatedCategories' => $categories,
                'faqs' => new stdClass(),
            ]);
        }
    }

    /**
     * @param string $order_uuid
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function confirmOrder(string $order_uuid): ViewContract
    {
        $order = Order::query()->where('uuid', $order_uuid)->first();

        if ((int) $order->getAttribute('orders')['confirmed'] !== Order::STATUS_CONFIRMED && (int) $order->getAttribute('orders')['confirmed'] !== Order::STATUS_DECLINED) {
            Notification::send(
                User::query()->scopes(['notifiableUsers'])->get(),
                new OrderConfirmNotification($order->toArray())
            );
    
            $order->unsetEventDispatcher();
    
            $order->forceFill([
                'orders->confirmed' => Order::STATUS_CONFIRMED
            ])->save();
        }

        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        return View::make('thanks', [
            'settings' => $this->getSettings() ?? [],
            'categories' => $categories,
            'order' => $order,
            'category' => new stdClass(),
            'steps' => [],
            'relatedCategories' => $categories,
            'faqs' => new stdClass(),
            'status' => $order->orderStatus()->first(),
            'states' => Lang::get('states'),
        ]);
    }

    /**
     * @param string $order_uuid
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function declineOrder(string $order_uuid): ViewContract
    {
        $order = Order::query()->where('uuid', $order_uuid)->first();

        if ((int) $order->getAttribute('orders')['confirmed'] !== Order::STATUS_CONFIRMED && (int) $order->getAttribute('orders')['confirmed'] !== Order::STATUS_DECLINED) {
            Notification::send(
                User::query()->scopes(['notifiableUsers'])->get(),
                new OrderDeclineNotification($order->toArray())
            );
    
            $order->unsetEventDispatcher();
    
            $order->forceFill([
                'orders->confirmed' => Order::STATUS_DECLINED
            ])->save();
        }

        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        return View::make('thanks', [
            'settings' => $this->getSettings() ?? [],
            'categories' => $categories,
            'order' => $order,
            'category' => new stdClass(),
            'steps' => [],
            'relatedCategories' => $categories,
            'faqs' => new stdClass(),
            'status' => $order->orderStatus()->first(),
            'states' => Lang::get('states'),
        ]);
    }


    /**
     * @param string $order_uuid
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function restoreOrder(string $order_uuid): ViewContract
    {
        $order = Order::query()->where('uuid', $order_uuid)->first();

        if ((int) $order->getAttribute('ordered_status') !== Order::STATUS_RESTORED && !$order->getAttribute('is_restored')) {
            Notification::send(
                User::query()->scopes(['notifiableUsers'])->get(),
                new OrderRestoreNotification($order->toArray())
            );
    
            $order->unsetEventDispatcher();
    
            $order->update([
                'ordered_status' => Order::STATUS_RESTORED,
                'is_restored' => true,
            ]);
        }

        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        return View::make('thanks', [
            'settings' => $this->getSettings() ?? [],
            'categories' => $categories,
            'order' => $order,
            'category' => new stdClass(),
            'steps' => [],
            'relatedCategories' => $categories,
            'faqs' => new stdClass(),
            'status' => $order->orderStatus()->first(),
            'states' => Lang::get('states'),
        ]);
    }

    /**
     * @param string $order_uuid
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function thanks(string $order_uuid): ViewContract
    {
        $order = Order::query()->where('uuid', $order_uuid)->first();

        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        return View::make('thanks', [
            'settings' => $this->getSettings() ?? [],
            'categories' => $categories,
            'order' => $order,
            'category' => new stdClass(),
            'steps' => [],
            'relatedCategories' => $categories,
            'faqs' => new stdClass(),
            'status' => $order->orderStatus()->first(),
            'states' => Lang::get('states'),
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function account(Request $request): ViewContract
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
            'states' => Lang::get('states'),
            'statuses' => Lang::get('admin/order.order_statuses'),
            'orders' => Order::query()->where('user_id', Auth::id())->with('orderStatus')->get() ?? [],
            'tab' => $request->get('tab')
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
        $userData = array_merge(
            $request->only(['name', 'phone', 'email', 'addresses']),
            [
                'addresses' => $request->get('addresses') ?? [],
                'mail_subscription' => $request->get('mail_subscription') ? 1 : 0,
            ]
        );

        if ($oldPassword = $request->get('old_password')) {
            if (Hash::check($oldPassword, $user->getAttribute('password'))) {
                $userData['password'] = $request->get('password');
            } else {
                return $this->json()->internalServerError([
                    'errors' => [
                        'old_password' => [
                            Lang::get('passwords.old')
                        ]
                    ],
                ]);
            }
        }

        $user->update($userData);

        return $this->json()->noContent();
    }

    /**
     * @param \App\Http\Requests\Admin\Order\UpdateRequest $request
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrderAddress(UpdateRequest $request, Order $order): JsonResponse
    {
        $order->unsetEventDispatcher();

        $order->update($request->all());

        $fedexService = new FedexService();

        $pdf = $fedexService->ship($order);

        if (isset($pdf['error'])) {
            $order->update([
                'is_label_trouble' => true,
            ]);

            return $this->json()->internalServerError([
                'errors' => [
                    'label' => $pdf['error'],
                ],
            ]);
        } else {
            Storage::disk('media')->put("pdf/fedex/{$order->getAttribute('uuid')}/label.pdf", $pdf);

            $pdfUrl = Config::get('app.url')."/media/pdf/fedex/{$order->getAttribute('uuid')}/label.pdf";

            $paymentData = $order->getAttribute('payment');

            $order->update([
                'is_label_trouble' => false,
                'payment' => array_merge($paymentData, ['fedexLabel' => $pdfUrl]),
                'fedex_status' => Order::STATUS_SHIPMENT_CREATED,
            ]);

            SendToMailLabelJob::dispatch($order->toArray(), $pdfUrl);

            return $this->json()->ok(['url' => $pdfUrl]);
        }
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPrice(Request $request): JsonResponse
    {
        $steps = json_decode($request->get('steps'), true);

        $category = Category::query()->whereKey($request->get('category_id'))->first();

        $subcategoryId = $category->getAttribute('subcategory_id');

        $category->increment('view_count', 1);

        while ($subcategoryId) {
            if ($subcategoryId) {
                $parentCategory = Category::query()->whereKey($subcategoryId)->first();

                if ($parentCategory) {
                    $parentCategory->increment('view_count', 1);
    
                    if ($subId = $parentCategory->getAttribute('subcategory_id')) {
                        $subcategoryId = $subId;
                    } else {
                        $subcategoryId = null;
                    }
                }
            }
        }

        $ids = [];

        $allStepsIds = [];

        $addToPrice = 0;

        $addPercent = 0;

        $isBroken = false;

        $isRecycle = false;

        $isLocked = false;

        foreach ($steps as $step) {
            $premiumPrice = DB::table('premium_price')
                ->where('step_id', $step['id'])
                ->where('category_id', $request->get('category_id'))
                ->first();

            if ($step['value'] === 'Brand New') {
                if ($category) {
                    foreach ($category->steps()->get() as $stepItem) {
                        if ($stepItem->stepName->is_checkbox) {
                            $premiumPriceForAccesory = DB::table('premium_price')
                                ->where('step_id', $stepItem->getKey())
                                ->where('category_id', $request->get('category_id'))
                                ->first();

                            if ($premiumPriceForAccesory) {
                                $addToPrice += $premiumPriceForAccesory->price_plus;
                            }
                        }
                    }
                }
            }

            if ($step['value'] === 'Broken') {
                $isBroken = true;
            }


            if ($step['id'] === 1111) {
                $isRecycle = true;
            }

            if (isset($step['is_device_locked']) && $step['value'] === 'Yes') {
                $isLocked = true;
            }

            if ($premiumPrice) {
                if ($pricePlus = $premiumPrice->price_plus) {
                    $addToPrice += $pricePlus;
                }

                if ($percentPlus = $premiumPrice->price_percent) {
                    $addPercent += $percentPlus;
                }
            }

            $stepCategory = StepName::query()->whereKey($step['name_id'])->first();

            if ($stepCategory && !$stepCategory->getAttribute('is_checkbox')) {
                $allStepsIds[] = $step['id'];
            }

            if ($stepCategory && $stepCategory->getAttribute('is_functional')) {
                if ($step['value'] === 'No') {
                    $isBroken = true;
                }
            }

            if ($category->getAttribute('is_parsed')) {
                if (isset($step['slug']) && isset($step['attribute'])) {
                    $id = $step['id'];
                    if ($step['value'] === 'Flawless') {
                        $id = Step::query()->where('value', 'Brand New')->first()->getKey();
                    }
                    $ids[] = $id;
                }
            } else {
                $id = $step['id'];
                // if ($step['value'] === 'Flawless') {
                //     $id = Step::query()->where('value', 'Brand New')->first()->getKey();
                // }
                $ids[] = $id;
            }
        }

        $resultPrice = 0;

        $couponPrice = 0;

        $prices = Price::query()->where('category_id', $request->get('category_id'))->get();

        foreach ($prices as $price) {
            $similar = array_intersect($ids, $price->getAttribute('steps_ids'));

            if (sizeof($ids) === sizeof($similar)) {
                if ($customPrice = $price->getAttribute('custom_price')) {
                    $resultPrice = $customPrice;
                } else {
                    $resultPrice = $price->getAttribute('price');
                }
            }
        }

        if ($addPercent) {
            $priceAddPercent = ((float) $resultPrice * $addPercent) / 100;

            $resultPrice = number_format((float) $resultPrice + $priceAddPercent, 2, '.', '');
        }

        if ($addToPrice) {
            $resultPrice = number_format((float) $resultPrice + $addToPrice, 2, '.', '');
        }

        if ($premiumPriceForDevice = $category->getAttribute('premium_price')) {
            $resultPrice += (float) $premiumPriceForDevice;
        }

        $resultPrice = number_format($resultPrice - ($resultPrice / 100 * 20), 2, '.', '');

        if ($coupon = $category->getAttribute('coupon')) {
            $couponPrice = $resultPrice;

            $resultPrice = number_format($resultPrice - (((float) $resultPrice * (float) $coupon['percent_value']) / 100), 2, '.', '');
        }

        if ($isBroken) {
            if ($priceForBroken = $category->getAttribute('price_for_broken')) {
                $resultPrice = $priceForBroken;
            }
        }

        if ($isRecycle) {
            $resultPrice = 0.1;
        }

        if ($isLocked) {
            $resultPrice = 0;
        }
        
        if (DB::table('statistics')->where('category_id', $category->getKey())->whereJsonContains('steps_ids', $allStepsIds)->exists()) {
            try {
                $stat = DB::table('statistics')->where('category_id', $category->getKey())->whereJsonContains('steps_ids', $allStepsIds);

                $stat->increment('steps_view_count', 1);
    
                $coefficient = (int) $stat->first()->steps_box_count / (int) $stat->first()->steps_view_count;
    
                $stat->update(['steps_coefficient' => (float) number_format($coefficient, 1, '.', '.')]);
            } catch (\Exception $e) {}
        } else {
            DB::table('statistics')->insert([
                'category_id' => $request->get('category_id'),
                'steps_box_count' => 0,
                'steps_view_count' => 1,
                'steps_coefficient' => 0,
                'steps_ids' => json_encode($allStepsIds),
                'price' => $resultPrice,
            ]);
        }

        if (DB::table('daily_statistics')->where('category_id', $category->getKey())->whereJsonContains('steps_ids', $allStepsIds)->exists()) {
            try {
                $stat = DB::table('daily_statistics')->where('category_id', $category->getKey())->whereJsonContains('steps_ids', $allStepsIds);

                $stat->increment('steps_view_count', 1);
    
                $coefficient = (int) $stat->first()->steps_box_count / (int) $stat->first()->steps_view_count;
    
                $stat->update(['steps_coefficient' => (float) number_format($coefficient, 1, '.', '.')]);
            } catch (\Exception $e) {}
        } else {
            DB::table('daily_statistics')->insert([
                'category_id' => $request->get('category_id'),
                'steps_box_count' => 0,
                'steps_view_count' => 1,
                'steps_coefficient' => 0,
                'steps_ids' => json_encode($allStepsIds),
                'price' => $resultPrice,
            ]);
        }

        return $this->json()->ok(['price' => $resultPrice, 'couponPrice' => $couponPrice]);
    }

    /**
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFedexLabel(Order $order): JsonResponse
    {
        $order->unsetEventDispatcher();

        $fedexService = new FedexService();

        $pdf = $fedexService->ship($order);

        if (isset($pdf['error'])) {
            $order->update([
                'is_label_trouble' => true,
            ]);

            return $this->json()->internalServerError([
                'errors' => [
                    'label' => $pdf['error'],
                ],
            ]);
        } else {
            Storage::disk('media')->put("pdf/fedex/{$order->getAttribute('uuid')}/label.pdf", $pdf);

            $pdfUrl = Config::get('app.url')."/media/pdf/fedex/{$order->getAttribute('uuid')}/label.pdf";

            $paymentData = $order->getAttribute('payment');

            $order->update([
                'is_label_trouble' => false,
                'payment' => array_merge($paymentData, ['fedexLabel' => $pdfUrl]),
                'fedex_status' => Order::STATUS_SHIPMENT_CREATED,
            ]);

            SendToMailLabelJob::dispatch($order->toArray(), $pdfUrl);

            return $this->json()->ok(['url' => $pdfUrl]);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCart(Request $request): JsonResponse
    {
        if(Auth::check()) {
            if (Cart::query()->where('user_id', '=', Auth::id())->exists()) {
                Cart::query()->where('user_id', '=', Auth::id())->delete();
            }
        }

        return $this->json()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCart(Request $request): JsonResponse
    {
        if(Auth::check()) {
            if (Cart::query()->where('user_id', '=', Auth::id())->exists()) {
                Cart::query()->where('user_id', '=', Auth::id())->update([
                    'orders' => json_encode($request->get('orders')),
                ]);
            } else {
                Cart::query()->create([
                    'user_id' => Auth::id(),
                    'orders' => $request->get('orders'),
                ]);
            }
        }

        return $this->json()->noContent();
    }

    /**
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToBox(string $slug, Request $request): JsonResponse
    {
        $slug = explode('/', $slug);

        $slug = end($slug);
        
        if(Auth::check()) {
            if (Cart::query()->where('user_id', '=', Auth::id())->exists()) {
                Cart::query()->where('user_id', '=', Auth::id())->update([
                    'orders' => json_encode($request->get('orders')),
                ]);
            } else {
                Cart::query()->create([
                    'user_id' => Auth::id(),
                    'orders' => $request->get('orders'),
                ]);
            }
        }

        $steps = $request->get('steps');

        $stepsIds = [];

        foreach ($steps as $step) {
            $stepsIds[] = (int) $step['id'];
        }

        $category = Category::query()->where('slug', $slug)->first();

        $category->increment('box_count', 1);

        if (DB::table('statistics')->where('category_id', $category->getKey())->whereJsonContains('steps_ids', $stepsIds)->exists()) {
            try {
                $stat = DB::table('statistics')->where('category_id', $category->getKey())->whereJsonContains('steps_ids', $stepsIds);

                $stat->increment('steps_box_count', 1);
    
                $coefficient = (int) $stat->first()->steps_box_count / (int) $stat->first()->steps_view_count;
    
                $stat->update(['steps_coefficient' => (float) number_format($coefficient, 1, '.', '.')]);
            } catch (\Exception $e) {}
        } else {
            DB::table('statistics')->insert([
                'category_id' => $request->get('category_id'),
                'steps_box_count' => 1,
                'steps_view_count' => 1,
                'steps_coefficient' => 1,
                'steps_ids' => json_encode($stepsIds),
                'price' => $request->get('price'),
            ]);
        }

        if (DB::table('daily_statistics')->where('category_id', $category->getKey())->whereJsonContains('steps_ids', $stepsIds)->exists()) {
            try {
                $stat = DB::table('daily_statistics')->where('category_id', $category->getKey())->whereJsonContains('steps_ids', $stepsIds);

                $stat->increment('steps_box_count', 1);
    
                $coefficient = (int) $stat->first()->steps_box_count / (int) $stat->first()->steps_view_count;
    
                $stat->update(['steps_coefficient' => (float) number_format($coefficient, 1, '.', '.')]);
            } catch (\Exception $e) {}
        } else {
            DB::table('daily_statistics')->insert([
                'category_id' => $request->get('category_id'),
                'steps_box_count' => 1,
                'steps_view_count' => 1,
                'steps_coefficient' => 1,
                'steps_ids' => json_encode($stepsIds),
                'price' => $request->get('price'),
            ]);
        }

        return $this->json()->noContent();
    }


    /**
     * @param \App\Category $category
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategory(Category $category): JsonResponse
    {
        $faqs = Faq::query()->whereKey($category->getAttribute('faq_id'))->first();

        $stepsArr = [];

        if ($steps = $category->steps()->get()) {
            foreach ($steps as $key => $step) {
                $stepsArr[$step->stepName->id]['items'][] = $step->toArray();
                $stepsArr[$step->stepName->id]['is_condition'] = $step->stepName->is_condition;
                $stepsArr[$step->stepName->id]['is_checkbox'] = $step->stepName->is_checkbox;
                $stepsArr[$step->stepName->id]['is_functional'] = $step->stepName->is_functional;
                $stepsArr[$step->stepName->id]['title'] = $step->stepName->title;
                if ($step->stepName->tip_id) {
                    $stepsArr[$step->stepName->id]['tip'] = Tip::query()->whereKey($step->stepName->tip_id)->first()->toArray() ?? [];
                }
            }
        }

        $resultArr = [];

        foreach ($stepsArr as $stepArr) {
            $resultArr[] = [
                'title' => $stepArr['title'],
                'items' => $stepArr['items'],
                'is_condition' => $stepArr['is_condition'],
                'is_checkbox' => $stepArr['is_checkbox'],
                'is_functional' => $stepArr['is_functional'],
                'tip' => isset($stepArr['tip']) ? $stepArr['tip'] : null,
            ];
        }

        return $this->json()->ok([
            'category' => $category,
            'steps' => $resultArr ?? [],
            'faqs' => $faqs ?? [],
        ]);
    }

    /**
     * @param string $slug
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function getByCategory(string $slug): ViewContract
    {
        $slug = explode('/', $slug);

        $slug = end($slug);

        $breadcrumbs = [];

        $category = Category::query()->where('slug', $slug)->first();

        if (!$category || $category->is_hidden) {
            throw new NotFoundHttpException();
        }

        $subcategoryId = $category->getAttribute('subcategory_id');

        while ($subcategoryId) {
            if ($subcategoryId) {
                $parentCategory = Category::query()->whereKey($subcategoryId)->first();

                if ($parentCategory) {
                    $breadcrumbs[] = [
                        'slug' => $parentCategory->getAttribute('url'),
                        'name' => $parentCategory->getAttribute('name'),
                    ];
    
                    if ($subId = $parentCategory->getAttribute('subcategory_id')) {
                        $subcategoryId = $subId;
                    } else {
                        $subcategoryId = null;
                    }
                }
            }
        }

        $breadcrumbs = array_reverse($breadcrumbs);

        $breadcrumbs[] = [
            'slug' => $category->getAttribute('url'),
            'name' => $category->getAttribute('name'),
        ];

        $relatedCategories = Category::query()
            ->where('subcategory_id', '=', $category->getKey())
            ->where('is_hidden', false)
            ->orderBy('custom_text', 'desc')
            ->orderBy('prod_year', 'desc')
            ->get();

        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNull('custom_text')
            ->whereNull('subcategory_id')
            ->get();

        $faqs = Faq::query()->whereKey($category->getAttribute('faq_id'))->first();

        $stepsArr = [];

        if ($steps = $category->steps()->get()) {
            foreach ($steps as $key => $step) {
                $stepsArr[$step->stepName->id]['items'][] = $step->toArray();
                $stepsArr[$step->stepName->id]['is_condition'] = $step->stepName->is_condition;
                $stepsArr[$step->stepName->id]['is_checkbox'] = $step->stepName->is_checkbox;
                $stepsArr[$step->stepName->id]['is_functional'] = $step->stepName->is_functional;
                $stepsArr[$step->stepName->id]['title'] = $step->stepName->title;
                if ($step->stepName->tip_id) {
                    $stepsArr[$step->stepName->id]['tip'] = Tip::query()->whereKey($step->stepName->tip_id)->first()->toArray() ?? [];
                }
            }
        }

        $resultArr = [];

        foreach ($stepsArr as $stepArr) {
            $resultArr[] = [
                'title' => $stepArr['title'],
                'items' => $stepArr['items'],
                'is_condition' => $stepArr['is_condition'],
                'is_checkbox' => $stepArr['is_checkbox'],
                'is_functional' => $stepArr['is_functional'],
                'tip' => isset($stepArr['tip']) ? $stepArr['tip'] : null,
            ];
        }

        return View::make('home', [
            'category' => $category,
            'steps' => $resultArr ?? [],
            'categories' => $categories,
            'settings' => $this->getSettings() ?? [],
            'relatedCategories' => $relatedCategories,
            'faqs' => $faqs ?? new stdClass(),
            'isMainPage' => false,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * @param \App\Http\Requests\Admin\Comment\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addComment(\App\Http\Requests\Admin\Comment\StoreRequest $request): JsonResponse
    {
        dd("OK");
        $comment = Comment::create(array_merge($request->all(), ['is_hidden' => true]));

        Notification::send(
            User::query()->scopes(['notifiableUsers'])->get(),
            new CommentNotification($comment->getKey())
        );

        return $this->json()->noContent();
    }
}

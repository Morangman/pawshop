<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\WarehouseTrait;
use App\Http\Requests\Admin\Order\StoreRequest;
use App\Http\Requests\Admin\Order\UpdateRequest;
use App\Mail\OrderReceivedMail;
use App\Order;
use App\OrderStatus;
use App\Price;
use App\Reminder;
use App\Services\FedexService;
use App\Step;
use App\StepName;
use App\SuspectIp;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Milon\Barcode\DNS1D;

/**
 * Class OrderController
 *
 * @package App\Http\Controllers\Admin
 */
class OrderController extends Controller
{
    use WarehouseTrait;

    /**
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): ViewContract
    {
        if ($request->get('status')) {
            $orders = Order::query()->where('ordered_status', '=', $request->get('status'))
                ->with('orderStatus')
                ->orderBy('id', 'desc')
                ->paginate(20);
        } else {
            $orders = null;
        }

        return View::make('admin.order.index', [
            'orders' => $orders,
            'statuses' => OrderStatus::query()->orderBy('order', 'asc')->get(),
            'orders_status' => (int) $request->get('status'),
            'is_new' => (int) $request->get('status') === Order::STATUS_NEW,
            'is_transit' => (int) $request->get('status') === Order::STATUS_TRANSIT,
            'is_delivered' => (int) $request->get('status') === Order::STATUS_ORDER_DELIVERED,
            'is_payed' => (int) $request->get('status') === Order::STATUS_PAID,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNotNull('custom_text')
            ->whereNotNull('subcategory_id')
            ->get();

        return View::make('admin.order.create', [
            'productByCategory' => $categories,
        ]);
    }

    /**
     * @param \App\Http\Requests\Admin\Order\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $orderData = array_merge(
            $request->all(),
            [
                'ip_address' => $request->ip()
            ]
        );

       $order = Order::create($orderData);

        if ($remindData = $request->get('reminder')) {
            Reminder::query()->create([
                'order_id' => $order->getKey(),
                'user_id' => Auth::id(),
                'email' => Auth::user()->getAttribute('email'),
                'title' => $remindData['title'] ?? Lang::get('admin/order.reminder.title'),
                'text' => $remindData['text'] ?? Lang::get('admin/order.reminder.text'),
                'reminder_date' => Carbon::parse($remindData['date']) ?? Carbon::tomorrow(),
            ]);
        }

        Session::flash(
            'success',
            Lang::get('admin/order.messages.create')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProduct(Request $request)
    {
        $category = Category::query()->whereKey((int) $request->get('product'))->first();
        
        $stepsArr = [];

        if ($steps = $category->steps()->get()) {
            foreach ($steps as $key => $step) {
                $stepsArr[$step->stepName->id]['items'][] = $step->toArray();
                $stepsArr[$step->stepName->id]['is_condition'] = $step->stepName->is_condition;
                $stepsArr[$step->stepName->id]['is_checkbox'] = $step->stepName->is_checkbox;
                $stepsArr[$step->stepName->id]['is_functional'] = $step->stepName->is_functional;
                $stepsArr[$step->stepName->id]['title'] = $step->stepName->title;
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
                'tip' => null,
            ];
        }

        return $this->json()->ok(['steps' => $resultArr, 'device' => $category->toArray()]);
    }

    /**
     * @param \App\Order $order
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Order $order): ViewContract
    {
        $d = new DNS1D();
        $d->setStorPath(storage_path().'/');

        $categories = Category::query()
            ->whereNotNull('custom_text')
            ->whereNotNull('subcategory_id')
            ->get();

        $steps = Step::query()->with('stepName')->get();

        $sortedSteps = [];

        foreach ($steps->toArray() as $step) {
            $sortedSteps[$step['step_name']['name']][] = $step;
        }

        $orderArray = $order->toArray();

        foreach($orderArray['orders']['order'] as $key => $orderData) {
            foreach($orderData['steps'] as $i => $step) {
                $orderArray['orders']['order'][$key]['steps'][$i]['variations'] = Step::query()->where('name_id', '=', $step['step_name']['id'])->get()->toArray();
            }
        }

        $suspectIp = SuspectIp::query()->where('ip_address', $order->getAttribute('ip_address'))->get();

        return View::make(
            'admin.order.edit',
            [
                'order' => $orderArray,
                'states' => Lang::get('states'),
                'statuses' => OrderStatus::query()->orderBy('order', 'asc')->get(),
                'productByCategory' => $categories,
                'suspectIp' => $suspectIp,
                'barcodeSrc' => $d->getBarcodeHTML($order->getKey(), 'EAN13', 3.5, 100),
                'steps' => $sortedSteps,
            ]
        );
    }

    /**
     * @param \App\Order $order
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function barcode(Order $order): ViewContract
    {
        $d = new DNS1D();
        $d->setStorPath(storage_path().'/');

        return View::make('admin.order.barcode', [
            'barcode' => $d->getBarcodePNG('11', 'C39', 3.6, 100),
            'order' => $order,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrder(Request $request, Order $order): JsonResponse
    {
        $ordersArray = $order->getAttribute('orders');

        $ordersArray['order'][(int) $request->get('key')] = $request->get('order');

        $orderTotalSumm = 0;

        foreach($ordersArray['order'] as $key => $orderData) {
            $ids = [];

            $category = Category::query()->whereKey($orderData['device']['id'])->first();

            $addToPrice = 0;

            $addPercent = 0;
    
            $isBroken = false;

            foreach($orderData['steps'] as $i => $step) {
                $step = Step::query()->whereKey((int) $step['id'])->with('stepName')->first()->toArray();

                $ordersArray['order'][$key]['steps'][$i] = $step;

                $premiumPrice = DB::table('premium_price')
                    ->where('step_id', $step['id'])
                    ->where('category_id', $orderData['device']['id'])
                    ->first();

                if ($step['value'] === 'Brand New') {
                    if ($category) {
                        foreach ($category->steps()->get() as $stepItem) {
                            if ($stepItem->stepName->is_checkbox) {
                                $premiumPriceForAccesory = DB::table('premium_price')
                                    ->where('step_id', $stepItem->getKey())
                                    ->where('category_id', $orderData['device']['id'])
                                    ->first();

                                $addToPrice += $premiumPriceForAccesory->price_plus;
                            }
                        }
                    }
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

                if ($stepCategory->getAttribute('is_functional')) {
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
                    if ($step['value'] === 'Flawless') {
                        $id = Step::query()->where('value', 'Brand New')->first()->getKey();
                    }
                    $ids[] = $id;
                }
            }

            $resultPrice = 0;

            $prices = Price::query()->where('category_id', $orderData['device']['id'])->get();
    
            foreach ($prices as $price) {
                if ( $price->getAttribute('is_parsed')) {
                    $similar = array_intersect($ids, $price->getAttribute('steps_ids'));
    
                    if (sizeof($ids) === sizeof($similar)) {
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
    
            if ($isBroken) {
                if ($priceForBroken = $category->getAttribute('price_for_broken')) {
                    $resultPrice = $priceForBroken;
                }
            }

            $ordersArray['order'][$key]['summ'] = $resultPrice * (int) $orderData['ctn'];
            $ordersArray['order'][$key]['total'] = $resultPrice * (int) $orderData['ctn'];

            $orderTotalSumm += (float) $resultPrice * (int) $orderData['ctn'];
        }
        
        $expShipping = 20;

        if ($order->getAttribute('exp_service')) {
            (float) $orderTotalSumm -= $expShipping;
        }

        if ($order->getAttribute('insurance')) {
            (float) $orderTotalSumm -= ((float) $orderTotalSumm  * 1)/100;
        }

        $order->update([
            'orders' => $ordersArray,
            'total_summ' => number_format((float) $orderTotalSumm, 2, '.', ''),
        ]);

        Session::flash(
            'success',
            Lang::get('admin/order.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addOrderedProduct(Request $request, Order $order): JsonResponse
    {
        $order->unsetEventDispatcher();

        $ordersArray = $order->getAttribute('orders');

        $newOrderDevice = $request->get('order');

        $ids = [];

        $category = Category::query()->whereKey($newOrderDevice['device']['id'])->first();

        $addToPrice = 0;

        $addPercent = 0;

        $orderTotalSumm = 0;

        $isBroken = false;

        foreach($newOrderDevice['steps'] as $i => $step) {
            $step = Step::query()->whereKey((int) $step['id'])->with('stepName')->first()->toArray();

            $premiumPrice = DB::table('premium_price')
                ->where('step_id', $step['id'])
                ->where('category_id', $newOrderDevice['device']['id'])
                ->first();

            if ($step['value'] === 'Brand New') {
                if ($category) {
                    foreach ($category->steps()->get() as $stepItem) {
                        if ($stepItem->stepName->is_checkbox) {
                            $premiumPriceForAccesory = DB::table('premium_price')
                                ->where('step_id', $stepItem->getKey())
                                ->where('category_id', $newOrderDevice['device']['id'])
                                ->first();

                            $addToPrice += $premiumPriceForAccesory->price_plus;
                        }
                    }
                }
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

            if ($stepCategory->getAttribute('is_functional')) {
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
                if ($step['value'] === 'Flawless') {
                    $id = Step::query()->where('value', 'Brand New')->first()->getKey();
                }
                $ids[] = $id;
            }
        }

        $resultPrice = 0;

        $prices = Price::query()->where('category_id', $newOrderDevice['device']['id'])->get();

        foreach ($prices as $price) {
            if ($price->getAttribute('is_parsed')) {
                $similar = array_intersect($ids, $price->getAttribute('steps_ids'));

                if (sizeof($ids) === sizeof($similar)) {
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

        if ($isBroken) {
            if ($priceForBroken = $category->getAttribute('price_for_broken')) {
                $resultPrice = $priceForBroken;
            }
        }

        $newOrderDevice['summ'] = $resultPrice * (int) $newOrderDevice['ctn'];
        $newOrderDevice['total'] = $resultPrice * (int) $newOrderDevice['ctn'];

        $ordersArray['order'][] = $newOrderDevice;

        foreach ($ordersArray['order'] as $orderData) {
            $orderTotalSumm += (float) $orderData['summ'];
        }

        $expShipping = 20;

        if ($order->getAttribute('exp_service')) {
            (float) $orderTotalSumm -= $expShipping;
        }

        if ($order->getAttribute('insurance')) {
            (float) $orderTotalSumm -= ((float) $orderTotalSumm  * 1)/100;
        }

        $order->update([
            'orders' => $ordersArray,
            'total_summ' => number_format((float) $orderTotalSumm, 2, '.', ''),
        ]);

        Session::flash(
            'success',
            Lang::get('admin/order.messages.update')
        );

        return $this->json()->noContent();
    }


    /**
     * @param \App\Http\Requests\Admin\Order\UpdateRequest $request
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Order $order): JsonResponse
    {
        if ($remindData = $request->get('reminder')) {
            Reminder::query()->create([
                'order_id' => $order->getKey(),
                'user_id' => Auth::id(),
                'email' => Auth::user()->getAttribute('email'),
                'title' => $remindData['title'] ?? Lang::get('admin/order.reminder.title'),
                'text' => $remindData['text'] ?? Lang::get('admin/order.reminder.text'),
                'reminder_date' => Carbon::parse($remindData['date']) ?? Carbon::tomorrow(),
            ]);
        }

        if ($suspectIp = $request->get('suspect_ip')) {
            SuspectIp::query()->create([
                'order_id' => $order->getKey(),
                'ip_address' => $suspectIp,
            ]);
        }

        $order->unsetEventDispatcher();

        $order->update($request->all());

        if ((int) $request->get('ordered_status') === Order::STATUS_RECEIVED && !$order->getAttribute('is_received_notify')) {
            try {
                $user = User::query()->whereKey($order->getAttribute('user_id'))->first();

                if ($user && $user->getAttribute('mail_subscription')) {
                    Mail::to($order->getAttribute('user_email'))
                        ->send(new OrderReceivedMail(
                            array_merge(
                                $order->toArray(),
                                [
                                    'user_name' => $user->getAttribute('name'),
                                ]
                            )
                        ));
                }

                $order->update([
                    'is_received_notify' => 1,
                ]);
            } catch (\Exception $e) {}
        }

        if ((int) $request->get('ordered_status') === Order::STATUS_PAID) {
            $order->update([
                'paid_date' => Carbon::now()
            ]);

            $this->setProductsToWarehouse($order);
        }

        Session::flash(
            'success',
            Lang::get('admin/order.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function deleteOrderProduct(Request $request, Order $order): JsonResponse
    {
        $ordersArray = $order->getAttribute('orders');

        array_splice($ordersArray['order'], 1, (int) $request->get('key'));

        $orderTotalSumm = 0;

        foreach($ordersArray['order'] as $key => $orderData) {
            $ids = [];

            $category = Category::query()->whereKey($orderData['device']['id'])->first();

            $addToPrice = 0;

            $addPercent = 0;
    
            $isBroken = false;

            foreach($orderData['steps'] as $i => $step) {
                $step = Step::query()->whereKey($step['id'])->with('stepName')->first()->toArray();

                $ordersArray['order'][$key]['steps'][$i] = $step;

                $premiumPrice = DB::table('premium_price')
                    ->where('step_id', $step['id'])
                    ->where('category_id', $orderData['device']['id'])
                    ->first();

                if ($step['value'] === 'Brand New') {
                    if ($category) {
                        foreach ($category->steps()->get() as $stepItem) {
                            if ($stepItem->stepName->is_checkbox) {
                                $premiumPriceForAccesory = DB::table('premium_price')
                                    ->where('step_id', $stepItem->getKey())
                                    ->where('category_id', $orderData['device']['id'])
                                    ->first();

                                $addToPrice += $premiumPriceForAccesory->price_plus;
                            }
                        }
                    }
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

                if ($stepCategory->getAttribute('is_functional')) {
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
                    if ($step['value'] === 'Flawless') {
                        $id = Step::query()->where('value', 'Brand New')->first()->getKey();
                    }
                    $ids[] = $id;
                }
            }

            $resultPrice = 0;

            $prices = Price::query()->where('category_id', $orderData['device']['id'])->get();
    
            foreach ($prices as $price) {
                if ( $price->getAttribute('is_parsed')) {
                    $similar = array_intersect($ids, $price->getAttribute('steps_ids'));
    
                    if (sizeof($ids) === sizeof($similar)) {
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
    
            if ($isBroken) {
                if ($priceForBroken = $category->getAttribute('price_for_broken')) {
                    $resultPrice = $priceForBroken;
                }
            }

            $ordersArray['order'][$key]['summ'] = $resultPrice * (int) $orderData['ctn'];
            $ordersArray['order'][$key]['total'] = $resultPrice * (int) $orderData['ctn'];

            $orderTotalSumm += (float) $resultPrice * (int) $orderData['ctn'];
        }
        
        $expShipping = 20;

        if ($order->getAttribute('exp_service')) {
            (float) $orderTotalSumm -= $expShipping;
        }

        if ($order->getAttribute('insurance')) {
            (float) $orderTotalSumm -= ((float) $orderTotalSumm  * 1)/100;
        }

        $order->update([
            'orders' => $ordersArray,
            'total_summ' => number_format((float) $orderTotalSumm, 2, '.', ''),
        ]);

        Session::flash(
            'success',
            Lang::get('admin/order.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(Order $order): JsonResponse
    {
        DB::table('order_device')->where('order_id', $order->getKey())->delete();

        $order->delete();

        Session::flash(
            'success',
            Lang::get('admin/order.messages.delete')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Order $order): JsonResponse
    {
        return $this->json()->ok($order);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     *  @return \Illuminate\Contracts\View\View
     *
     * @throws \Exception
     */
    public function search(Request $request): ViewContract
    {
        $orders = Order::query()
            ->with('orderStatus')
            ->when(
                $request->get('search'),
                function ($query, $search) {
                    $search = ucwords($search);
                    
                    $keyword = "%{$search}%";

                    $query->where('address->name', 'like', $keyword)
                        ->orWhere('id', 'like', $keyword)
                        ->orWhere('tracking_number', 'like', $keyword);
                }
            )
            ->paginate(20);

        return View::make('admin.order.index', [
            'orders' => $orders,
            'statuses' => OrderStatus::query()->orderBy('order', 'asc')->get(),
            'orders_status' => null,
            'is_new' => false,
            'is_transit' => false,
            'is_delivered' => false,
            'is_payed' => false,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \InvalidArgumentException
     */
    public function getAll(Request $request): JsonResponse
    {
        $orderStatus = $request->get('order_status');

        $fedexStatus = $request->get('fedex_status');

        $orders = Order::query()
            ->with('orderStatus')
            ->when(
                $orderStatus,
                function ($q) use ($orderStatus) {
                    $q->where('ordered_status', $orderStatus);
                }
            )
            ->when(
                $fedexStatus,
                function ($q) use ($fedexStatus) {
                    if ($fedexStatus === Order::STATUS_IN_TRANSIT) {
                        $q->where('fedex_status', Order::STATUS_IN_TRANSIT)
                            ->orWhere('fedex_status', Order::STATUS_ARRIVED)
                            ->orWhere('fedex_status', Order::STATUS_ON_FEDEX_VEHICLE);
                    } else {
                        $q->where('fedex_status', $fedexStatus);
                    }
                }
            )
            ->when(
                $request->get('search'),
                function ($query, $search) {
                    $search = ucwords($search);

                    $keyword = "%{$search}%";

                    $query->where('address->name', 'like', $keyword)
                        ->orWhere('id', 'like', $keyword)
                        ->orWhere('tracking_number', 'like', $keyword);
                }
            )
            ->when(
                $request->get('by'),
                function ($q, $sort) use ($request) {
                    $q->orderBy($sort, $request->get('dir', 'asc'));
                }
            )
            ->paginate(20);

        return $this->json()->ok($orders);
    }
}

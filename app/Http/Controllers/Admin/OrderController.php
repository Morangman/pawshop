<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\StoreRequest;
use App\Http\Requests\Admin\Order\UpdateRequest;
use App\Order;
use App\Product;
use App\Reminder;
use App\SuspectIp;
use Carbon\Carbon;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * Class OrderController
 *
 * @package App\Http\Controllers\Admin
 */
class OrderController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): ViewContract
    {
        return View::make('admin.order.index');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        $productByCategory = [];

        $products = Product::query()->where('is_hidden', false)->get();

        foreach ($products as $product) {
            $productCategory = $product->category()->first();
            $productByCategory[$productCategory->getAttribute('name')][] = [
                'id' => $product->getKey(),
                'title' => $product->getAttribute('title'),
                'variations' => $product->getAttribute('variations'),
            ];
        }

        return View::make('admin.order.create', [
            'productByCategory' => $productByCategory,
        ]);
    }

    /**
     * @param \App\Http\Requests\Admin\Order\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $orderData = array_merge($request->all(), ['ip_address' => $request->ip()]);

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
     * @param \App\Order $order
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Order $order): ViewContract
    {
        $productByCategory = [];

        $products = Product::all();

        foreach ($products as $product) {
            $productCategory = $product->category()->first();
            $productByCategory[$productCategory->getAttribute('name')][] = [
                'id' => $product->getKey(),
                'title' => $product->getAttribute('title'),
                'variations' => $product->getAttribute('variations'),
            ];
        }

        $suspectIp = SuspectIp::query()->where('ip_address', $order->getAttribute('ip_address'))->get();

        return View::make(
            'admin.order.edit',
            [
                'order' => $order,
                'productByCategory' => $productByCategory,
                'suspectIp' => $suspectIp,
            ]
        );
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

        $order->update($request->all());

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
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \InvalidArgumentException
     */
    public function getAll(Request $request): JsonResponse
    {
        $orderStatus = $request->get('order_status');

        $orders = Order::query()
            ->when(
                $orderStatus,
                function ($q) use ($orderStatus) {
                    $q->where('ordered_status', $orderStatus);
                }
            )
            ->when(
                $request->get('search'),
                function ($query, $search) {
                    $keyword = "%{$search}%";

                    $query->where('name', 'like', $keyword);
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

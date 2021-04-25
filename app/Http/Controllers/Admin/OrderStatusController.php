<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderStatus\StoreRequest;
use App\Http\Requests\Admin\OrderStatus\UpdateRequest;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * Class OrderStatusController
 *
 * @package App\Http\Controllers\Admin
 */
class OrderStatusController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): ViewContract
    {
        return View::make('admin.order_status.index');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        return View::make('admin.order_status.create');
    }

    /**
     * @param \App\Http\Requests\Admin\OrderStatus\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function store(StoreRequest $request): JsonResponse
    {
        OrderStatus::create($request->all());

        Session::flash(
            'success',
            Lang::get('admin/order-status.messages.create')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\OrderStatus $orderStatus
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(OrderStatus $orderStatus): ViewContract
    {
        return View::make(
            'admin.order_status.edit',
            [
                'status' => $orderStatus,
            ]
        );
    }

    /**
     * @param \App\Http\Requests\Admin\OrderStatus\UpdateRequest $request
     * @param \App\OrderStatus $orderStatus
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function update(UpdateRequest $request, OrderStatus $orderStatus): JsonResponse
    {
        $orderStatus->update($request->all());

        Session::flash(
            'success',
            Lang::get('admin/order-status.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\OrderStatus $orderStatus
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(OrderStatus $orderStatus): JsonResponse
    {
        $orderStatus->delete();

        Session::flash(
            'success',
            Lang::get('admin/order-status.messages.delete')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\OrderStatus $orderStatus
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(OrderStatus $orderStatus): JsonResponse
    {
        return $this->json()->ok($orderStatus);
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
        $statuses = OrderStatus::query()
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

        return $this->json()->ok($statuses);
    }
}

<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\WarehouseStatus;
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
 * Class WarehouseStatusController
 *
 * @package App\Http\Controllers\Admin
 */
class WarehouseStatusController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): ViewContract
    {
        return View::make('admin.warehouse_status.index');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        return View::make('admin.warehouse_status.create');
    }

    /**
     * @param \App\Http\Requests\Admin\OrderStatus\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function store(StoreRequest $request): JsonResponse
    {
        WarehouseStatus::create($request->all());

        Session::flash(
            'success',
            Lang::get('admin/warehouse-status.messages.create')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\WarehouseStatus $warehouseStatus
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(WarehouseStatus $warehouseStatus): ViewContract
    {
        return View::make(
            'admin.warehouse_status.edit',
            [
                'status' => $warehouseStatus,
            ]
        );
    }

    /**
     * @param \App\Http\Requests\Admin\OrderStatus\UpdateRequest $request
     * @param \App\WarehouseStatus $warehouseStatus
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function update(UpdateRequest $request, WarehouseStatus $warehouseStatus): JsonResponse
    {
        $warehouseStatus->update($request->all());

        Session::flash(
            'success',
            Lang::get('admin/warehouse-status.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\WarehouseStatus $warehouseStatus
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(WarehouseStatus $warehouseStatus): JsonResponse
    {
        $warehouseStatus->delete();

        Session::flash(
            'success',
            Lang::get('admin/warehouse-status.messages.delete')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\OrderStatus $warehouseStatus
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(WarehouseStatus $warehouseStatus): JsonResponse
    {
        return $this->json()->ok($warehouseStatus);
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
        $statuses = WarehouseStatus::query()
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

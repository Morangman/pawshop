<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Warehouse\StoreRequest;
use App\Http\Requests\Admin\Warehouse\UpdateRequest;
use App\Warehouse;
use App\WarehouseStatus;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use McMatters\Helpers\Helpers\ModelHelper;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class WarehouseController
 *
 * @package App\Http\Controllers\Admin
 */
class WarehouseController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): ViewContract
    {
        if ($request->get('status')) {
            $products = Warehouse::query()->where('status', '=', $request->get('status'))
                ->with(['warehouseStatus', 'media'])
                ->orderBy('id', 'desc')
                ->paginate(20);
        } else {
            $products = null;
        }

        return View::make('admin.warehouse.index', [
            'products' => $products,
            'statuses' => WarehouseStatus::query()->orderBy('order', 'asc')->get(),
            'status' => (int) $request->get('status'),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        return View::make('admin.warehouse.create', [
            'categories' => Category::query()
                ->whereNotNull('custom_text')
                ->whereNotNull('subcategory_id')
                ->get(),
            'statuses' => WarehouseStatus::query()->orderBy('order', 'asc')->get(),
        ]);
    }

    /**
     * @param \App\Http\Requests\Admin\Warehouse\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $category = Category::query()->whereKey((int) $request->get('category_id'))->first();

        $data = $request->except(
            [
                'product_name',
                'steps',
            ]
        ) + [
            'product_name' => $category->getAttribute('name'),
            'steps' => [],
        ];

        $warehouse = Warehouse::create($data);

        $this->handleDocuments($request, $warehouse);

        Session::flash(
            'success',
            Lang::get('admin/warehouse.messages.create')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Warehouse $warehouse
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Warehouse $warehouse): ViewContract
    {
        return View::make(
            'admin.warehouse.edit',
            [
                'warehouse' => $warehouse->append('warehouse_images'),
                'statuses' => WarehouseStatus::query()->orderBy('order', 'asc')->get(),
                'categories' => Category::query()
                    ->whereNotNull('custom_text')
                    ->whereNotNull('subcategory_id')
                    ->get(),
            ]
        );
    }

    /**
     * @param \App\Http\Requests\Admin\Warehouse\UpdateRequest $request
     * @param \App\Warehouse $warehouse
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function update(UpdateRequest $request, Warehouse $warehouse): JsonResponse
    {
        $category = Category::query()->whereKey((int) $request->get('category_id'))->first();

        $data = $request->except(
            [
                'product_name',
            ]
        ) + [
            'product_name' => $category->getAttribute('name'),
        ];

        $warehouse->update($data);

        $this->handleDocuments($request, $warehouse);

        Session::flash(
            'success',
            Lang::get('admin/warehouse.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Warehouse $warehouse
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(Warehouse $warehouse): JsonResponse
    {
        $warehouse->delete();

        Session::flash(
            'success',
            Lang::get('admin/warehouse.messages.delete')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Warehouse $warehouse
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Warehouse $warehouse): JsonResponse
    {
        return $this->json()->ok($warehouse);
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
        $productStatus = $request->get('status');

        $products = Warehouse::query()
            ->with(['warehouseStatus', 'media'])
            ->when(
                $productStatus,
                function ($q) use ($productStatus) {
                    $q->where('status', $productStatus);
                }
            )
            ->when(
                $request->get('search'),
                function ($query, $search) {
                    $keyword = "%{$search}%";

                    $query->where('imei', 'like', $keyword)
                        ->orWhere('product_name', 'like', $keyword)
                        ->orWhere('serial_number', 'like', $keyword)
                        ->orWhere('order_id', 'like', $keyword);
                }
            )
            ->when(
                $request->get('by'),
                function ($q, $sort) use ($request) {
                    $q->orderBy($sort, $request->get('dir', 'asc'));
                }
            )
            ->paginate(20);

        return $this->json()->ok($products);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Warehouse $warehouse
     *
     * @return void
     *
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    protected function handleDocuments(Request $request, Warehouse $warehouse): void
    {
        $images = $request->file('media', []);

        foreach ($images as $image) {
            $warehouse->addMedia($image)
                ->toMediaCollection(Warehouse::MEDIA_COLLECTION_WAREHOUSE);
        }
    }

    /**
     * @param \App\Warehouse $warehouse
     * @param \Spatie\MediaLibrary\Models\Media $media
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMedia(Warehouse $warehouse, Media $media): JsonResponse
    {
        try {
            if ($media->getAttribute('collection_name') === Warehouse::MEDIA_COLLECTION_WAREHOUSE
                && ModelHelper::doesMorphedBelongToParent($media, $warehouse, 'model')
            ) {
                $warehouse->deleteMedia($media->getKey());
            }
        } catch (\Throwable $e) {
            return $this->json()->badRequest(['message' => $e->getMessage()]);
        }

        return $this->json()->noContent();
    }
}

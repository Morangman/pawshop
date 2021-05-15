<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Faq;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Warehouse\StoreRequest;
use App\Http\Requests\Admin\Warehouse\UpdateRequest;
use App\Warehouse;
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
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): ViewContract
    {
        return View::make('admin.warehouse.index');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        return View::make('admin.warehouse.create');
    }

    /**
     * @param \App\Http\Requests\Admin\Warehouse\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $warehouse = Warehouse::create($request->all());

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
                'warehouse' => $warehouse,
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
        $warehouse->update($request->all());

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
        $products = Warehouse::query()
            ->when(
                $request->get('search'),
                function ($query, $search) {
                    $keyword = "%{$search}%";

                    $query->where('imei', 'like', $keyword)
                        ->orWhere('product_name', 'like', $keyword)
                        ->orWhere('serial_number', 'like', $keyword);
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
        $images = $request->file('product_images', []);

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

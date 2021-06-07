<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Coupon;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Coupon\StoreRequest;
use App\Http\Requests\Admin\Coupon\UpdateRequest;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * Class CouponController
 *
 * @package App\Http\Controllers\Admin
 */
class CouponController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): ViewContract
    {
        return View::make('admin.coupon.index');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        return View::make('admin.coupon.create', [
            'categories' => Category::query()
                ->whereNull('custom_text')
                ->get(),
        ]);
    }

    /**
     * @param \App\Http\Requests\Admin\Coupon\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function store(StoreRequest $request): JsonResponse
    {
        Coupon::create($request->all());

        Session::flash(
            'success',
            Lang::get('admin/coupon.messages.create')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Coupon $coupon
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Coupon $coupon): ViewContract
    {
        return View::make(
            'admin.coupon.edit',
            [
                'coupon' => $coupon,
                'categories' => Category::query()
                    ->whereNull('custom_text')
                    ->get(),
            ]
        );
    }

    /**
     * @param \App\Http\Requests\Admin\Coupon\UpdateRequest $request
     * @param \App\Coupon $coupon
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function update(UpdateRequest $request, Coupon $coupon): JsonResponse
    {
        $coupon->update($request->all());

        Session::flash(
            'success',
            Lang::get('admin/coupon.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Coupon $coupon
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(Coupon $coupon): JsonResponse
    {
        $coupon->delete();

        Session::flash(
            'success',
            Lang::get('admin/coupon.messages.delete')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Coupon $coupon
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Coupon $coupon): JsonResponse
    {
        return $this->json()->ok($coupon);
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
        $coupons = Coupon::query()
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

        return $this->json()->ok($coupons);
    }
}

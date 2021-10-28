<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\AllDailyStatistic;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\StoreRequest;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * Class AllDailyStatisticsController
 *
 * @package App\Http\Controllers\Admin
 */
class AllDailyStatisticsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function index(): ViewContract
    {
        return View::make('admin.all-daily-statistics.index', [
            'viewsCount' => AllDailyStatistic::query()->whereDate('date', Carbon::yesterday())->sum('steps_view_count'),
        ]);
    }

    /**
     * @param \App\Http\Requests\Admin\Setting\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function update(StoreRequest $request): JsonResponse
    {

        Session::flash(
            'success',
            Lang::get('admin/daily-statistics.messages.create')
        );

        return $this->json()->noContent();
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
        $statistics = AllDailyStatistic::query()
            ->when(
                $request->get('search'),
                function ($query, $search) {
                    $keyword = "%{$search}%";

                    $query->where('title', 'like', $keyword);
                }
            )
            ->when(
                $request->get('date'),
                function ($query, $search) {
                    $query->whereDate('date', $search);
                }
            )
            ->when(
                !$request->get('date'),
                function ($query, $search) {
                    $query->whereDate('date', Carbon::yesterday());
                }
            )
            ->when(
                $request->get('by'),
                function ($q, $sort) use ($request) {
                    $q->orderBy($sort, $request->get('dir', 'asc'));
                }
            )
            ->paginate(20);

        return $this->json()->ok(['data' => $statistics, 'viewsCount' => AllDailyStatistic::query()->whereDate('date', $request->get('date') ?  $request->get('date') : Carbon::yesterday())->sum('steps_view_count')]);
    }
}

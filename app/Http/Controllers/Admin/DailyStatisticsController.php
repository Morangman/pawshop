<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\StoreRequest;
use App\Step;
use App\StepName;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * Class DailyStatisticsController
 *
 * @package App\Http\Controllers\Admin
 */
class DailyStatisticsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function index(): ViewContract
    {
        return View::make('admin.daily-statistics.index', [
            'viewsCount' => DB::table('daily_statistics')->sum('steps_view_count'),
            'boxCount' => DB::table('daily_statistics')->sum('steps_box_count'),
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
        $statistics = DB::table('daily_statistics')
            ->select('*')
            ->join('categories', 'daily_statistics.category_id', '=', 'categories.id')
            ->when(
                $request->get('by'),
                function ($q, $sort) use ($request) {
                    $q->orderBy($sort, $request->get('dir', 'asc'));
                }
            )
            ->get()
            ->toArray();
        
        $array = [];

        foreach($statistics as $stat) {
            $array[] = (array) $stat;
        }

        foreach ($array as $key => $value) {
            $stepsIds = json_decode($value['steps_ids']);

            $steps = Step::query()->whereIn('id', $stepsIds)->get()->toArray();

            foreach ($steps as $i => $step) {
                $stepName = StepName::query()->where('id', $step['name_id'])->first()->toArray();

                if ($stepName['is_checkbox']) {
                    unset($steps[$i]);
                } else {
                    $steps[$i]['step_name'] = $stepName;
                }
            }

            $array[$key]['steps'] = $steps;
        }

        return $this->json()->ok($this->paginate($array, 20, (int) $request->get('page'), []));
    }

    public function paginate($items, $perPage = 20, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}

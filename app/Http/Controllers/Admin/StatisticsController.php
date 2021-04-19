<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Setting;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\StoreRequest;
use App\Http\Requests\Admin\Setting\UpdateRequest;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

/**
 * Class StatisticsController
 *
 * @package App\Http\Controllers\Admin
 */
class StatisticsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function index(): ViewContract
    {
        return View::make('admin.statistics.index');
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
            Lang::get('admin/statistics.messages.create')
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
        $cats = Category::query()
            ->where('is_hidden', false)
            ->whereNotNull('custom_text')
            ->whereNotNull('subcategory_id')
            ->get();

        foreach ($cats as $cat) {
            $coefficient = (int) $cat->getAttribute('box_count') / (int) $cat->getAttribute('view_count');

            $cat->update(['coefficient' => $coefficient]);     
        }

        $categories = Category::query()
            ->where('is_hidden', false)
            ->whereNotNull('custom_text')
            ->whereNotNull('subcategory_id')
            ->when(
                $request->get('search'),
                function ($query, $search) {
                    $keyword = "%{$search}%";

                    $query->where('name', 'like', $keyword)
                        ->orWhere('slug', 'like', $keyword)
                        ->orWhere('id', 'like', $keyword);
                }
            )
            ->orderBy('view_count', 'desc')
            ->paginate(20);

        return $this->json()->ok($categories);
    }
}

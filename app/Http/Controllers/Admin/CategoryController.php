<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Faq;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreRequest;
use App\Http\Requests\Admin\Category\UpdateRequest;
use App\Setting;
use App\Step;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * Class CategoryController
 *
 * @package App\Http\Controllers\Admin
 */
class CategoryController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): ViewContract
    {
        return View::make('admin.category.index');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        return View::make('admin.category.create', [
            'categories' => Category::query()
                ->whereNull('custom_text')
                ->whereNull('subcategory_id')
                ->get(),
            'faqs' => Faq::all(),
            'steps' => Step::all(),
        ]);
    }

    /**
     * @param \App\Http\Requests\Admin\Category\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $category = Category::create($request->all());

        if ($steps = $request->get('steps')) {
            $stepsIds = [];
            foreach ($steps as $step) {
                $stepsIds[] = $step['id'];
            }

            $steps = Step::find($stepsIds);

            $category->steps()->attach($steps);
        }

        $this->handleDocuments($request, $category);

        Session::flash(
            'success',
            Lang::get('admin/category.messages.create')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Category $category
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Category $category): ViewContract
    {
        return View::make(
            'admin.category.edit',
            [
                'category' => $category,
                'categories' => Category::query()
                    ->whereNull('custom_text')
                    ->whereNull('subcategory_id')
                    ->get(),
                'faqs' => Faq::all(),
                'steps' => Step::all(),
                'categorysteps' => $category->steps()->get(),
            ]
        );
    }

    /**
     * @param \App\Http\Requests\Admin\Category\UpdateRequest $request
     * @param \App\Category $category
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function update(UpdateRequest $request, Category $category): JsonResponse
    {
        if ($steps = $request->get('steps')) {
            $stepsIds = [];
            foreach ($steps as $step) {
                $stepsIds[] = $step['id'];
            }

            $steps = Step::find($stepsIds);

            $category->steps()->sync($steps);
        }

        $category->update($request->all());

        $this->handleDocuments($request, $category);

        Session::flash(
            'success',
            Lang::get('admin/category.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Category $category
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(Category $category): JsonResponse
    {
        $category->delete();

        Session::flash(
            'success',
            Lang::get('admin/category.messages.delete')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Category $category
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Category $category): JsonResponse
    {
        return $this->json()->ok($category);
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
        $categories = Category::query()
            ->when(
                $request->get('search'),
                function ($query, $search) {
                    $keyword = "%{$search}%";

                    $query->where('name', 'like', $keyword)
                        ->orWhere('slug', 'like', $keyword)
                        ->orWhere('id', 'like', $keyword);
                }
            )
            ->when(
                $request->get('by'),
                function ($q, $sort) use ($request) {
                    $q->orderBy($sort, $request->get('dir', 'asc'));
                }
            )
            ->paginate(20);

        return $this->json()->ok($categories);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Category $category
     *
     * @return void
     *
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    protected function handleDocuments(Request $request, Category $category): void
    {
        if ($categoryPreviewImage = $request->file('image')) {
            $media = $category->addMedia($categoryPreviewImage)
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }
    }
}

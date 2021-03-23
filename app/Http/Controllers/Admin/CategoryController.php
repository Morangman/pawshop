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
use App\StepName;
use App\Tip;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
     * @param string $slug
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(string $slug): ViewContract
    {
        $category = Category::query()->where('slug', $slug)->first();

        $stepsArr = [];

        $priceVariations = [];

        $pricesByCategory = DB::table('prices')->where('category_id', $category->getKey())->get();

        foreach ($pricesByCategory as $price) {
            $priceVariations[] = [
                'steps' => Step::query()->whereIn('id', json_decode($price->steps_ids))->get()->toArray(),
                'price' => $price->price
            ];
        }

        if ($steps = $category->steps()->get()) {
            foreach ($steps as $key => $step) {
                $stepsArr[$step->stepName->id]['id'] = $step->stepName->id;
                $stepsArr[$step->stepName->id]['items'][] = $step->toArray();
                $stepsArr[$step->stepName->id]['is_condition'] = $step->stepName->is_condition;
                $stepsArr[$step->stepName->id]['is_checkbox'] = $step->stepName->is_checkbox;
                $stepsArr[$step->stepName->id]['is_functional'] = $step->stepName->is_functional;
                $stepsArr[$step->stepName->id]['title'] = $step->stepName->title;
            }
        }

        $resultArr = [];

        foreach ($stepsArr as $stepArr) {
            $resultArr[] = [
                'id' => $stepArr['id'],
                'title' => $stepArr['title'],
                'items' => $stepArr['items'],
                'items_variations' => $stepArr['items'],
                'is_condition' => $stepArr['is_condition'],
                'is_checkbox' => $stepArr['is_checkbox'],
                'is_functional' => $stepArr['is_functional'],
            ];
        }

        $stepsByName = [];

        foreach (StepName::all() as $stepName) {
            $stepsByName[] = array_merge(
                $stepName->toArray(),
                [
                    'items' => Step::query()->where('name_id', $stepName->getKey())->get()->toArray(),
                    'items_variations' => Step::query()->where('name_id', $stepName->getKey())->get()->toArray(),
                ]
            );
        }

        return View::make(
            'admin.category.edit',
            [
                'category' => $category,
                'categories' => Category::query()
                    ->whereNull('custom_text')
                    ->whereNull('subcategory_id')
                    ->get(),
                'faqs' => Faq::all(),
                'steps' => $stepsByName,
                'categorysteps' => $resultArr,
                'prices' => $priceVariations,
            ]
        );
    }

    /**
     * @param \App\Http\Requests\Admin\Category\UpdateRequest $request
     * @param string $slug
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function update(UpdateRequest $request, string $slug): JsonResponse
    {
        $category = Category::query()->where('slug', $slug)->first();

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
     * @param string $slug
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(string $slug): JsonResponse
    {
        $category = Category::query()->where('slug', $slug)->first();

        $category->delete();

        Session::flash(
            'success',
            Lang::get('admin/category.messages.delete')
        );

        return $this->json()->noContent();
    }

    /**
     * @param string $slug
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(string $slug): JsonResponse
    {
        $category = Category::query()->where('slug', $slug)->first();

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

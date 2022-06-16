<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Faq;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreRequest;
use App\Http\Requests\Admin\Category\UpdateRequest;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * Class CategoryController
 *
 * @package App\Http\Controllers\Admin
 */
class CategoryController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): ViewContract
    {
        return View::make('admin.category.index');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        return View::make('admin.category.create', [
            'category' => null,
            'categories' => Category::query()
                ->whereNull('custom_text')
                ->get(),
            'faqs' => Faq::all(),
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
        $category = Category::create(array_merge($request->all(), ['user_id' => Auth::id()]));

        if (!$request->get('slug')) {
            $category->update([
                'slug' => preg_replace('~[^\pL\d]+~u', '-', strtolower($category->getAttribute('name'))),
            ]);
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
                    ->get(),
                'faqs' => Faq::all(),
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
        $hidden = (int) $request->get('is_hidden');

        if (Category::query()->where('subcategory_id', '=', $category->getKey())->exists()) {
            $subCat = Category::query()->where('subcategory_id', '=', $category->getKey())->first();

            if ($subCat) {
                while($subCat) {
                    Category::query()->where('subcategory_id', '=', $subCat->getKey())->update([
                        'is_hidden' => $hidden ?? 0,
                    ]);

                    if ($exist = Category::query()->where('subcategory_id', '=', $subCat->getKey())->first()) {
                        $subCat = $exist;
                    } else {
                        $subCat = false;
                    }
                }
            }
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
        DB::table('category_step')->where('category_id', $category->getKey())->delete();

        DB::table('prices')->where('category_id', $category->getKey())->delete();

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
            ->whereNull('custom_text')
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
            $path = '/media/' . Category::MEDIA_COLLECTION_CATEGORY . '_' . $category->getKey() . '_' . str_replace(' ', '_', strtolower($category->getAttribute('name'))) . '.webp';

            $fullPath = Config::get('app.url') . $path;

            Image::make($categoryPreviewImage)->encode('webp', 90)->resize(260, 260)->save(public_path($path));

            $media = $category->addMedia($categoryPreviewImage)
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update([
                'image' => $media->getFullUrl(),
                'compressed_image' => $fullPath,
            ]);
        }

        if ($productImageUrl = $request->get('image_url')) {
            $path = '/media/' . Category::MEDIA_COLLECTION_CATEGORY . '_' . str_replace(' ', '_', strtolower($category->getAttribute('name'))) . '.webp';

            $fullPath = Config::get('app.url') . $path;

            Image::make($productImageUrl)->encode('webp', 90)->resize(260, 260)->save(public_path($path));

            $media = $category->addMediaFromUrl($productImageUrl)
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update([
                'image' => $media->getFullUrl(),
                'compressed_image' => $fullPath,
            ]);
        }
    }
}

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

        return View::make('admin.category.create', [
            'category' => null,
            'categories' => Category::query()
                ->whereNull('custom_text')
                ->whereNull('subcategory_id')
                ->get(),
            'faqs' => Faq::all(),
            'steps' => $stepsByName,
            'categorysteps' => [],
            'prices' => [],
            'premiumPrices' => [],
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

        if ($requestSteps = $request->get('steps')) {
            $stepsIds = [];
            foreach ($requestSteps as $step) {
                foreach ($step['items'] as $item) {
                    $stepsIds[] = $item['id'];
                }
            }

            $steps = Step::find($stepsIds);

            $category->steps()->attach($steps);

            $attributes = [];

            foreach ($requestSteps as $step) {
                foreach ($step['items'] as $item) {
                    $attributes[$step['id']][] = [
                        'id' => $item['id']
                    ];
                }
            }
    
            $combinations = $this->array_cartesian_product($attributes);

            foreach ($combinations as $combination) {
                $combinationIds = [];
                foreach ($combination as $item) {
                    $combinationIds[] = (int) $item['id'];
                }

                DB::table('prices')->insert([
                    'category_id' => $category->getKey(),
                    'steps_ids' => json_encode($combinationIds),
                    'price' => 0,
                    'is_parsed' => 1,
                ]);              
            }
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

        $pricesByCategory = DB::table('prices')->where('category_id', $category->getKey())->get()->toArray();

        $idsArray = [];

        foreach ($pricesByCategory as $index => $obj) {
            $idsArray[] = json_decode($obj->steps_ids);
        }

        $idsArray = array_unique($idsArray, SORT_REGULAR);

        foreach ($idsArray as $ids) {
            $price = DB::table('prices')->where('category_id', $category->getKey())->whereJsonContains('steps_ids', $ids)->first();
            $steps = Step::query()->whereIn('id', $ids)->get()->toArray();

            foreach ($steps as $i => $step) {
                if ($category->getAttribute('is_parsed')) {
                    $step['value'] === 'Brand New' ? $steps[$i]['value'] = 'Flawless' : $step['value'];
                }
            }

            $priceVariations[] = [
                'steps' => $steps,
                'price' => $price->price,
                'custom_price' => $price->custom_price,
                'id' => $price->id,
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

        $premiumPrices = [];

        foreach(DB::table('premium_price')->where('category_id', $category->getKey())->get() as $price) {
            $premiumPrices[] = [
                'id' => $price->id,
                'step_id' => $price->step_id,
                'step_name' => Step::query()->whereKey($price->step_id)->first()->getAttribute('value'),
                'price_plus' => $price->price_plus,
                'price_percent' => $price->price_percent,
            ];
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
                'premiumPrices' => $premiumPrices,
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

        if ((int) $request->get('is_hidden') === 1){
            if (Category::query()->where('subcategory_id', '=', $category->getKey())->exists()) {
                Category::query()->where('subcategory_id', '=', $category->getKey())->update([
                    'is_hidden' => 1,
                ]);
            }
        } else {
            if (Category::query()->where('subcategory_id', '=', $category->getKey())->exists()) {
                Category::query()->where('subcategory_id', '=', $category->getKey())->update([
                    'is_hidden' => 0,
                ]);
            }
        }

        if ($requestSteps = $request->get('steps')) {
            $stepsIds = [];
            foreach ($requestSteps as $step) {
                foreach ($step['items'] as $item) {
                    $stepsIds[] = $item['id'];

                    if((int) $item['name_id'] === 6 && !DB::table('premium_price')->where('category_id', '=', $category->getKey())->where('step_id', '=', (int) $item['id'])->exists()) {
                        DB::table('premium_price')->insert([
                            'category_id' =>  $category->getKey(),
                            'step_id' => (int) $item['id'],
                            'price_plus' => null,
                            'price_percent' => null,

                        ]);
                    }
                }
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
     * @param \Illuminate\Http\Request $request
     * @param string $slug
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generatePricesVariations(Request $request, string $slug): JsonResponse
    {
        $category = Category::query()->where('slug', $slug)->first();

        DB::table('prices')->where('category_id', $category->getKey())->delete();

        $attributes = [];

        foreach ($category->steps()->get()->toArray() as $step) {
            $nameId = Step::query()->whereKey((int) $step['id'])->first()->getAttribute('name_id');

            $attributes[$nameId][] = [
                'id' => (int) $step['id']
            ];
        }

        $combinations = $this->array_cartesian_product($attributes);

        foreach ($combinations as $combination) {
            $combinationIds = [];
            foreach ($combination as $item) {
                $combinationIds[] = $item['id'];
            }

            DB::table('prices')->insert([
                'category_id' => $category->getKey(),
                'steps_ids' => json_encode($combinationIds),
                'price' => 0,
                'is_parsed' => 1,
            ]);              
        }

        Session::flash(
            'success',
            Lang::get('admin/category.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $slug
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePrice(Request $request, string $slug): JsonResponse
    {
        $category = Category::query()->where('slug', $slug)->first();

        if ($price = $request->get('price')) {
            $ids = [];

            foreach ($request->get('steps') as $step) {
                $ids[] = (int) $step['id'];
            }

            DB::table('prices')->where('category_id', $category->getKey())->whereJsonContains('steps_ids', $ids)->update([
                'price' => (float) $price,
            ]);
        }
        
        if ($customPrice = $request->get('custom_price')) {
            $ids = [];

            foreach ($request->get('steps') as $step) {
                $ids[] = (int) $step['id'];
            }

            DB::table('prices')->where('category_id', $category->getKey())->whereJsonContains('steps_ids', $ids)->update([
                'custom_price' => (float) $customPrice,
            ]);
        }

        Session::flash(
            'success',
            Lang::get('admin/category.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $slug
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePremiumPrice(Request $request, string $slug): JsonResponse
    {
        $category = Category::query()->where('slug', $slug)->first();

        if ($id = $request->get('id')) {
            DB::table('premium_price')->where('id', (int) $id)->where('step_id', (int) $request->get('step_id'))->where('category_id', $category->getKey())->update([
                'price_plus' => (float) $request->get('price_plus'),
                'price_percent' => (float) $request->get('price_percent'),
            ]);
        }

        Session::flash(
            'success',
            Lang::get('admin/category.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $slug
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function deletePremiumPrice(Request $request, string $slug): JsonResponse
    {
        $category = Category::query()->where('slug', $slug)->first();
        
        if ($id = $request->get('id')) {
            DB::table('premium_price')->where('id', (int) $id)->where('step_id', (int) $request->get('step_id'))->where('category_id', $category->getKey())->delete();
        }

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
            $media = $category->addMedia($categoryPreviewImage)
                ->toMediaCollection(Category::MEDIA_COLLECTION_CATEGORY);

            $category->update(['image' => $media->getFullUrl()]);
        }
    }

    function array_cartesian_product($arrays)
    {
        $result = array();
        $arrays = array_values($arrays);
        $sizeIn = sizeof($arrays);
        $size = $sizeIn > 0 ? 1 : 0;
        foreach ($arrays as $array)
            $size = $size * sizeof($array);
        for ($i = 0; $i < $size; $i ++)
        {
            $result[$i] = array();
            for ($j = 0; $j < $sizeIn; $j ++)
                array_push($result[$i], current($arrays[$j]));
            for ($j = ($sizeIn -1); $j >= 0; $j --)
            {
                if (next($arrays[$j]))
                    break;
                elseif (isset ($arrays[$j]))
                    reset($arrays[$j]);
            }
        }
        return $result;
    }

    function maxValueInArray($array, $keyToSearch)
    {
        $currentMax = NULL;
        foreach($array as $arr)
        {
            foreach($arr as $key => $value)
            {
                if ($key == $keyToSearch && ($value >= $currentMax))
                {
                    $currentMax = $value;
                }
            }
        }

        return $currentMax;
    }

    function my_array_unique($array, $keep_key_assoc = false){
        $duplicate_keys = array();
        $tmp = array();       
    
        foreach ($array as $key => $val){
            // convert objects to arrays, in_array() does not support objects
            if (is_object($val))
                $val = (array)$val;
    
            if (!in_array($val, $tmp))
                $tmp[] = $val;
            else
                $duplicate_keys[] = $key;
        }
    
        foreach ($duplicate_keys as $key)
            unset($array[$key]);
    
        return $keep_key_assoc ? $array : array_values($array);
    }
}

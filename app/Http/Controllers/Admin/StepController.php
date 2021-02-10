<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Step;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Step\StoreRequest;
use App\Http\Requests\Admin\Step\UpdateRequest;
use App\Setting;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * Class StepController
 *
 * @package App\Http\Controllers\Admin
 */
class StepController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): ViewContract
    {
        return View::make('admin.step.index');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        return View::make('admin.step.create', [
            'steps' => Step::all(),
        ]);
    }

    /**
     * @param \App\Http\Requests\Admin\Step\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $step = Step::create($request->all());

        $this->handleDocuments($request, $step);

        Session::flash(
            'success',
            Lang::get('admin/step.messages.create')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Step $step
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Step $step): ViewContract
    {
        return View::make(
            'admin.step.edit',
            [
                'category' => $step,
                'steps' => Step::all(),
            ]
        );
    }

    /**
     * @param \App\Http\Requests\Admin\Step\UpdateRequest $request
     * @param \App\Step $step
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function update(UpdateRequest $request, Step $step): JsonResponse
    {
        $step->update($request->all());

        $this->handleDocuments($request, $step);

        Session::flash(
            'success',
            Lang::get('admin/step.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Step $step
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(Step $step): JsonResponse
    {
        $step->delete();

        Session::flash(
            'success',
            Lang::get('admin/step.messages.delete')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Step $step
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Step $step): JsonResponse
    {
        return $this->json()->ok($step);
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
        $steps = Step::query()
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

        return $this->json()->ok($steps);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Step $step
     *
     * @return void
     *
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    protected function handleDocuments(Request $request, Step $step): void
    {
        if ($stepPreviewImage = $request->file('image')) {
            $media = $step->addMedia($stepPreviewImage)
                ->toMediaCollection(Step::MEDIA_COLLECTION_PRODUCT);

            $step->update(['image' => $media->getFullUrl()]);
        }
    }
}

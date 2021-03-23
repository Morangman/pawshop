<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Step;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Step\StoreRequest;
use App\Http\Requests\Admin\Step\UpdateRequest;
use App\StepName;
use App\Tip;
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
            'tips' => Tip::all(),
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
        $stepName = StepName::create($request->all());

        foreach ($request->get('steps') as $step) {
            Step::query()->create([
                'name_id' => $stepName->getKey(),
                'value' => $step['value'],
                'decryption' => isset($step['decryption']) ? $step['decryption'] : null,
            ]);
        }

        Session::flash(
            'success',
            Lang::get('admin/step.messages.create')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\StepName $stepName
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(StepName $stepName): ViewContract
    {
        return View::make(
            'admin.step.edit',
            [
                'step' => $stepName,
                'tips' => Tip::all(),
                'steps' => Step::query()->where('name_id', $stepName->getKey())->get(),
            ]
        );
    }

    /**
     * @param \App\Http\Requests\Admin\Step\UpdateRequest $request
     * @param \App\StepName $stepName
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function update(UpdateRequest $request, StepName $stepName): JsonResponse
    {
        $stepName->update($request->all());

        foreach ($request->get('steps') as $step) {
            if (isset($step['id'])) {
                Step::query()->whereKey($step['id'])->update($step);
            } else {
                Step::query()->create([
                    'name_id' => $stepName->getKey(),
                    'value' => $step['value'],
                    'decryption' => isset($step['decryption']) ? $step['decryption'] : null,
                ]);
            }
        }

        Session::flash(
            'success',
            Lang::get('admin/step.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\StepName $stepName
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(StepName $stepName): JsonResponse
    {
        $stepName->delete();

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
     *
     * @throws \Exception
     */
    public function deleteItem(Step $step): JsonResponse
    {
        $step->delete();

        Session::flash(
            'success',
            Lang::get('admin/step.messages.delete')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\StepName $stepName
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(StepName $stepName): JsonResponse
    {
        return $this->json()->ok($stepName);
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
        $steps = StepName::query()
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
}

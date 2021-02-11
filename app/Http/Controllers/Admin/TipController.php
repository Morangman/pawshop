<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Tip;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tip\StoreRequest;
use App\Http\Requests\Admin\Tip\UpdateRequest;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * Class tipController
 *
 * @package App\Http\Controllers\Admin
 */
class TipController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): ViewContract
    {
        return View::make('admin.tip.index');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        return View::make('admin.tip.create', [
            'tips' => Tip::all(),
        ]);
    }

    /**
     * @param \App\Http\Requests\Admin\Tip\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function store(StoreRequest $request): JsonResponse
    {
        Tip::create($request->all());

        Session::flash(
            'success',
            Lang::get('admin/tip.messages.create')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Tip $tip
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Tip $tip): ViewContract
    {
        return View::make(
            'admin.tip.edit',
            [
                'tip' => $tip,
                'tips' => Tip::all(),
            ]
        );
    }

    /**
     * @param \App\Http\Requests\Admin\Tip\UpdateRequest $request
     * @param \App\Tip $tip
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function update(UpdateRequest $request, Tip $tip): JsonResponse
    {
        $tip->update($request->all());

        Session::flash(
            'success',
            Lang::get('admin/tip.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Tip $tip
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(Tip $tip): JsonResponse
    {
        $tip->delete();

        Session::flash(
            'success',
            Lang::get('admin/tip.messages.delete')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Tip $tip
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Tip $tip): JsonResponse
    {
        return $this->json()->ok($tip);
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
        $tips = Tip::query()
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

        return $this->json()->ok($tips);
    }
}

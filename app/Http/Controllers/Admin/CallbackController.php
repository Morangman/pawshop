<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Callback;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Callback\StoreRequest;
use App\Http\Requests\Admin\Callback\UpdateRequest;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * Class CallbackController
 *
 * @package App\Http\Controllers\Admin
 */
class CallbackController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): ViewContract
    {
        return View::make('admin.callback.index');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        return View::make('admin.callback.create');
    }

    /**
     * @param \App\Http\Requests\Admin\Callback\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        Callback::create($request->all());

        Session::flash(
            'success',
            Lang::get('admin/callback.messages.create')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Callback $callback
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Callback $callback): ViewContract
    {
        return View::make(
            'admin.callback.edit',
            [
                'callback' => $callback,
            ]
        );
    }

    /**
     * @param \App\Http\Requests\Admin\Callback\UpdateRequest $request
     * @param \App\Callback $callback
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Callback $callback): JsonResponse
    {
        $callback->update($request->all());

        Session::flash(
            'success',
            Lang::get('admin/callback.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Callback $callback
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(Callback $callback): JsonResponse
    {
        $callback->delete();

        Session::flash(
            'success',
            Lang::get('admin/callback.messages.delete')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Callback $callback
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Callback $callback): JsonResponse
    {
        return $this->json()->ok($callback);
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
        $callbacks = Callback::query()
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

        return $this->json()->ok($callbacks);
    }
}
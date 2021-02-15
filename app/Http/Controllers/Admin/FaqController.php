<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Faq;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Faq\StoreRequest;
use App\Http\Requests\Admin\Faq\UpdateRequest;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * Class FaqController
 *
 * @package App\Http\Controllers\Admin
 */
class FaqController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): ViewContract
    {
        return View::make('admin.faq.index');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        return View::make('admin.faq.create');
    }

    /**
     * @param \App\Http\Requests\Admin\Faq\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function store(StoreRequest $request): JsonResponse
    {
        Faq::create($request->all());

        Session::flash(
            'success',
            Lang::get('admin/faq.messages.create')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Faq $faq
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Faq $faq): ViewContract
    {
        return View::make(
            'admin.faq.edit',
            [
                'faq' => $faq,
            ]
        );
    }

    /**
     * @param \App\Http\Requests\Admin\Faq\UpdateRequest $request
     * @param \App\Faq $faq
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function update(UpdateRequest $request, Faq $faq): JsonResponse
    {
        $faq->update($request->all());

        Session::flash(
            'success',
            Lang::get('admin/faq.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Faq $faq
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(Faq $faq): JsonResponse
    {
        $faq->delete();

        Session::flash(
            'success',
            Lang::get('admin/faq.messages.delete')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Faq $faq
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Faq $faq): JsonResponse
    {
        return $this->json()->ok($faq);
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
        $faqs = Faq::query()
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

        return $this->json()->ok($faqs);
    }
}

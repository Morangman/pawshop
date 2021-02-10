<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\User;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use McMatters\LaravelRoles\Traits\HasRole;

/**
 * Class UserController
 *
 * @package App\Http\Controllers\Admin
 */
class UserController extends Controller
{
    use HasRole;

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): ViewContract
    {
        return View::make('admin.user.index');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        return View::make('admin.user.create');
    }

    /**
     * @param \App\Http\Requests\Admin\User\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $user = User::create($request->all());

        $user->attachRole($request->get('role'));

        Session::flash(
            'success',
            Lang::get('admin/user.messages.create')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\User $user
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(User $user): ViewContract
    {
        return View::make(
            'admin.user.edit',
            [
                'user' => $user,
                'role' => $user->getAttribute('roles'),
            ]
        );
    }

    /**
     * @param \App\Http\Requests\Admin\User\UpdateRequest $request
     * @param \App\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, User $user): JsonResponse
    {
        $user->update($request->all());

        $user->detachRole();

        $user->attachRole($request->get('role'));

        Session::flash(
            'success',
            Lang::get('admin/user.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(User $user): JsonResponse
    {
        $user->delete();

        Session::flash(
            'success',
            Lang::get('admin/user.messages.delete')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(User $user): JsonResponse
    {
        return $this->json()->ok($user);
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
        $users = User::query()
            ->when(
                $request->get('search'),
                function ($query, $search) {
                    $keyword = "%{$search}%";

                    $query->where('name', 'like', $keyword)
                        ->orWhere('email', 'like', $keyword);
                }
            )
            ->when(
                $request->get('by'),
                function ($q, $sort) use ($request) {
                    $q->orderBy($sort, $request->get('dir', 'asc'));
                }
            )
            ->with('roles')
            ->paginate(20);

        return $this->json()->ok($users);
    }
}

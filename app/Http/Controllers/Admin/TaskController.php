<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Task\StoreRequest;
use App\Http\Requests\Admin\Task\UpdateRequest;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * Class TaskController
 *
 * @package App\Http\Controllers\Admin
 */
class TaskController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): ViewContract
    {
        return View::make('admin.task.index');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        return View::make('admin.task.create');
    }

    /**
     * @param \App\Http\Requests\Admin\Task\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        Task::create($request->all());

        Session::flash(
            'success',
            Lang::get('admin/task.messages.create')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Task $task
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Task $task): ViewContract
    {
        return View::make(
            'admin.task.edit',
            [
                'task' => $task,
            ]
        );
    }

    /**
     * @param \App\Http\Requests\Admin\Task\UpdateRequest $request
     * @param \App\Task $task
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Task $task): JsonResponse
    {
        $task->update($request->all());

        Session::flash(
            'success',
            Lang::get('admin/task.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Task $task
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(Task $task): JsonResponse
    {
        $task->delete();

        Session::flash(
            'success',
            Lang::get('admin/task.messages.delete')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Task $task
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Task $task): JsonResponse
    {
        return $this->json()->ok($task);
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
        $taskStatus = $request->get('task_status');

        $tasks = Task::query()
            ->when(
                $taskStatus,
                function ($q) use ($taskStatus) {
                    $q->where('task_status', $taskStatus);
                }
            )
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

        return $this->json()->ok($tasks);
    }
}

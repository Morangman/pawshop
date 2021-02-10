<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Comment\StoreRequest;
use App\Http\Requests\Admin\Comment\UpdateRequest;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * Class CommentController
 *
 * @package App\Http\Controllers\Admin
 */
class CommentController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): ViewContract
    {
        return View::make('admin.comment.index');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): ViewContract
    {
        return View::make('admin.comment.create');
    }

    /**
     * @param \App\Http\Requests\Admin\Comment\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        Comment::create($request->all());

        Session::flash(
            'success',
            Lang::get('admin/comment.messages.create')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Comment $comment
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Comment $comment): ViewContract
    {
        return View::make(
            'admin.comment.edit',
            [
                'comment' => $comment,
            ]
        );
    }

    /**
     * @param \App\Http\Requests\Admin\Comment\UpdateRequest $request
     * @param \App\Comment $comment
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Comment $comment): JsonResponse
    {
        $comment->update($request->all());

        Session::flash(
            'success',
            Lang::get('admin/comment.messages.update')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Comment $comment
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(Comment $comment): JsonResponse
    {
        $comment->delete();

        Session::flash(
            'success',
            Lang::get('admin/comment.messages.delete')
        );

        return $this->json()->noContent();
    }

    /**
     * @param \App\Comment $comment
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Comment $comment): JsonResponse
    {
        return $this->json()->ok($comment);
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
        $comments = Comment::query()
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

        return $this->json()->ok($comments);
    }
}

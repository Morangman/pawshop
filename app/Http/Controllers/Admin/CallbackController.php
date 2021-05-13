<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Callback;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Callback\StoreRequest;
use App\Http\Requests\Admin\Callback\UpdateRequest;
use App\Mail\MessageMail;
use App\Message;
use App\User;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Prophecy\Call\Call;

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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \InvalidArgumentException
     */
    public function sendEmail(Request $request): JsonResponse
    {
        $lastMessageText = null;

        if (Callback::query()->where('email', '=', $request->get('email'))->exists()) {
            $callback = Callback::query()->where('email', '=', $request->get('email'))->first();

            $lastMessage = Message::query()->where('chat_id', '=', $callback->getKey())->where('sender', '=', Callback::SENDER_FROM)->orderBy('created_at', 'desc')->orderBy('time', 'desc')->first();

            if ($lastMessage) {
                $lastMessageText = $lastMessage->getAttribute('text');
            }

            Message::query()->create(
                array_merge(
                    $request->all(),
                    [
                        'sender' => Callback::SENDER_TO,
                        'chat_id' => $callback->getKey(),
                    ]
                )
            );
        } else {
            $user = User::query()->where('email', '=', $request->get('email'))->first();

            $callback = Callback::query()->create(
                array_merge(
                    $request->all(),
                    [
                        'name' => $user ? $user->getAttribute('name') : 'Client',
                        'sender' => Callback::SENDER_TO,
                    ]
                )
            );

            Message::query()->create(
                array_merge(
                    $request->all(),
                    [
                        'sender' => Callback::SENDER_TO,
                        'chat_id' => $callback->getKey(),
                    ]
                )
            );
        }

        $callback->messages()->update(['viewed' => Callback::IS_VIEWED]);

        try {
            Mail::to($request->get('email'))
                ->send(
                    new MessageMail(
                        [
                            'text' => $request->get('text'),
                            'last_message' => $lastMessageText
                        ]
                    )
                );
        } catch (\Exception $e) {}

        Session::flash(
            'success',
            Lang::get('admin/callback.messages.update')
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
        $callback = Callback::query()->whereKey($callback->getKey())->with('messages')->first();

        $user = User::query()->where('email', '=', $callback->getAttribute('email'))->first();

        $callback->messages()->update(['viewed' => Callback::IS_VIEWED]);

        return View::make(
            'admin.callback.edit',
            [
                'callback' => $callback,
                'user' => $user,
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
        Message::query()->where('chat_id', '=', $callback->getKey())->delete();

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
        $callback = Callback::query()->whereKey($callback->getKey())->with('messages')->first();

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
            ->with('newMessages')
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

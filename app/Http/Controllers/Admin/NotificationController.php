<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * Class NotificationController
 *
 * @package App\Http\Controllers\Admin
 */
class NotificationController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): ViewContract
    {
        return View::make('admin.notification.index');
    }

    /**
     * @param \Illuminate\Notifications\DatabaseNotification $notification
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view(DatabaseNotification $notification): ViewContract
    {
        $notification->markAsRead();

        return View::make(
            'admin.notification.view',
            [
                'notification' => $notification,
            ]
        );
    }

    /**
     * @param \Illuminate\Notifications\DatabaseNotification $notification
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(DatabaseNotification $notification): JsonResponse
    {
        $notification->delete();

        Session::flash(
            'success',
            Lang::get('admin/notification.messages.delete')
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
    public function getAll(Request $request): JsonResponse
    {
        $notifications = Auth::user()
            ->notifications($request->get('by'), $request->get('dir', 'asc'))
            ->paginate(20);

        return $this->json()->ok($notifications);
    }
}

<?php

namespace App\Exceptions;

use App\Category;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Lang;
use stdClass;
use App\Http\Controllers\Traits\SettingTrait;

class Handler extends ExceptionHandler
{
    use SettingTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) 
        {
            $categories = Category::query()
                ->where('is_hidden', false)
                ->whereNull('custom_text')
                ->whereNull('subcategory_id')
                ->get();

            return response()->make(view('404', [
                'settings' => $this->getSettings() ?? [],
                'categories' => $categories,
                'category' => [],
                'steps' => [],
                'user' => \Illuminate\Support\Facades\Auth::user(),
                'relatedCategories' => $categories,
                'faqs' => new stdClass(),
                'states' => Lang::get('states'),
                'statuses' => Lang::get('admin/order.order_statuses'),
                'orders' => [],
                'tab' => ''
            ]), 404);
        } 

        return parent::render($request, $exception);
    }
}

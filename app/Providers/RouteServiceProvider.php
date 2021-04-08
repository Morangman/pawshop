<?php

namespace App\Providers;

use App\Category;
use App\Comment;
use App\Faq;
use App\Order;
use App\OrderStatus;
use App\Setting;
use App\Step;
use App\StepName;
use App\Task;
use App\Tip;
use App\User;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Route;
use Spatie\MediaLibrary\Models\Media;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->bindModels();

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();
        $this->mapAdminRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * @return void
     */
    protected function mapAdminRoutes(): void
    {
        Route::prefix('admin')
            ->middleware(['web', 'auth'])
            ->name('admin')
            ->namespace("{$this->namespace}\Admin")
            ->group($this->app->basePath().'/routes/admin.php');
    }

    /**
     * @return void
     */
    protected function bindModels(): void
    {
        $models = [
            'user' => User::class,
            'category' => Category::class,
            'tip' => Tip::class,
            'step' => Step::class,
            'stepName' => StepName::class,
            'media' => Media::class,
            'status' => OrderStatus::class,
            'comment' => Comment::class,
            'faq' => Faq::class,
            'task' => Task::class,
            'setting' => Setting::class,
            'nitification' => Notification::class,
        ];

        foreach ($models as $key => $class) {
            Route::pattern($key, '[0-9]+');
            Route::model($key, $class);
        }
    }
}

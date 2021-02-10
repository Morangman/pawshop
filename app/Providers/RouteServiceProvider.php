<?php

namespace App\Providers;

use App\Category;
use App\Comment;
use App\Order;
use App\Product;
use App\Setting;
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
            'product' => Product::class,
            'media' => Media::class,
            'order' => Order::class,
            'comment' => Comment::class,
            'setting' => Setting::class,
            'nitification' => Notification::class,
        ];

        foreach ($models as $key => $class) {
            Route::pattern($key, '[0-9]+');
            Route::model($key, $class);
        }
    }
}

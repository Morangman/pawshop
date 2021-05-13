<?php

declare(strict_types = 1);

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Class ExtensionServiceProvider
 *
 * @package App\Providers
 */
class ExtensionServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->registerBladeDirectives();
    }

    /**
     * @return void
     */
    protected function registerBladeDirectives(): void
    {
        Blade::directive('route', function ($expression) {
            return "<?php echo URL::route({$expression}); ?>";
        });

        Blade::directive(
            'active_menu_class',
            function (string $route, string $class = 'active') {
                return "<?php echo Request::routeIs({$route}.'.*') ? '{$class}' : ''; ?>";
            }
        );
    }
}
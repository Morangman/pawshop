<?php

declare(strict_types = 1);

namespace App\Providers;

use App\Observers\OrderObserver;
use App\Order;
use Illuminate\Support\ServiceProvider;

/**
 * Class ModelObserverServiceProvider
 *
 * @package App\Providers
 */
class ModelObserverServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $observers = [
        Order::class => OrderObserver::class,
    ];

    /**
     * @return void
     */
    public function boot(): void
    {
        foreach ($this->observers as $class => $observer) {
            /** @var \Illuminate\Database\Eloquent\Model $class */
            $class::observe($observer);
        }
    }
}

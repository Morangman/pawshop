<?php

declare(strict_types = 1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use const null;

/**
 * Class CaheckRole
 *
 * @package App\Http\Middleware
 */
class CaheckRole
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (null !== $user && !$user->hasAnyRole(['admin', 'manager'])) {
            return Redirect::to('/');
        }

        return $next($request);
    }
}

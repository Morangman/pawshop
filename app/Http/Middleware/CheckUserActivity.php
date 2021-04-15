<?php

declare(strict_types = 1);

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

use const null;

/**
 * Class CheckUserActivity
 *
 * @package App\Http\Middleware
 */
class CheckUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (null !== $user) {
            $lastAccessedAt = $user->getAttribute('last_accessed_at');

            if ($lastAccessedAt === null || $lastAccessedAt->diffInMinutes() >= 3) {
                $user->update([
                    'last_accessed_at' => Carbon::now(),
                ]);
            }
        }

        return $next($request);
    }
}
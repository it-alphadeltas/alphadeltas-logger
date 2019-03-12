<?php
declare(strict_types=1);

namespace AlphaDeltas\Logger\Http\Middleware;

use Closure;
use Log;

class LogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info('Endpoint was hit.', [
            'url'    => $request->getRequestUri(),
            'method' => $request->method(),
            'params' => $request->input(),
            'user'   => optional($request->user())->only('id', 'email', 'name'),
        ]);

        return $next($request);
    }
}

<?php
declare(strict_types=1);

namespace AlphaDeltas\Logger\Http\Middleware;

use Illuminate\Support\Facades\Route;
use Closure;
use Log;

class LogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  array                    $fields
     *
     * @return mixed
     */
    public function handle($request, Closure $next, array $fields = [])
    {
        Log::info('Endpoint was hit.', [
            'url'    => $request->getRequestUri(),
            'method' => $request->method(),
            'params' => $this->getParams($request, $fields),
            'user'   => optional($request->user())->only('id', 'email', 'name'),
        ]);

        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param array $fields
     * @return array|mixed
     */
    private function getParams($request, array $fields = [])
    {
        if (!empty($fields)) return collect($request->input())->only($fields)->all();
        return $this->getParamsUsingConfig($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param array $fields
     * @return mixed
     */
    private function getParamsUsingConfig($request, array $fields = [])
    {
        /** @var null|\Illuminate\Routing\Route $route */
        $route = null;
        try {
            $route = Route::getRoutes()->match($request);
        } catch(\Exception $e) {}
        if(!$route) return $request->input();

        $config = config('logger.routes');

        // take method specific fields list if it is set
        $fields = array_get($config, "{$route->uri()}.{$request->method()}");
        if(!$fields) {
            $fields = array_get($config, "{$route->uri()}");
        }

        if(!$fields) return $request->input();

        //check if config contains closure
        if(
            (
                (is_array($fields) && count($fields) === 2 && class_exists($fields[0])) ||
                is_string($fields)
            ) &&
            is_callable($fields)
        ) {
            try {
                return call_user_func($fields, $request, $route);
            } catch (\Exception $e) {
                Log::error('LogMiddleware::getFieldsFromConfig error', [
                    'route' => $route->uri(),
                    'method' => $request->method(),
                    'exception' => $e->getMessage(),
                ]);
            }
        }

        return (!empty($fields)) ? collect($request->input())->only($fields)->all() : $request->input();
    }
}

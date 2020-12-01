<?php

return [
    /*
     * Routes fields to log
     *
     * Specify a list of fields for all methods of a route
     * ```
     *  'api/some-random-url-part/{url-param}' => ['id', 'name'],
     * ```
     *
     * Specify a list of fields for specific method of a route
     * ```
     *  'api/some-random-url-part/{url-param}' => [
     *      'GET' => ['id', 'name'],
     *  ],
     * ```
     *
     * Provide callback to resolve array to log for a route
     * NOTE: callback method should be static and accept $request and $route
     * ```
     *  'api/some-random-url-part' => 'App\Http\Logger\SomeRandomFieldsResolver::getParams',
     *  'api/some-random-url-part/{type}' => [\App\Http\Logger\SomeRandomFieldsResolver::class, 'getParamsByType'],
     * ```
     */
];
<?php

namespace App\Http\Middleware;

use Closure;

class CheckSensitiveEmail
{
    public function handle($request, Closure $next)
    {
        $requestArray = $request->all();
        $requestArray['email'] = strtolower($requestArray['email']);
        $request->replace($requestArray);

        return $next($request);
    }
}

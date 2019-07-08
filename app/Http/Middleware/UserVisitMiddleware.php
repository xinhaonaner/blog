<?php

namespace App\Http\Middleware;

use App\Models\UserVisit;
use Closure;

class UserVisitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        UserVisit::create([
            'ip' => $request->ip(),
            'url' => $request->url(),
            'method' => $request->method(),
            'request_params' => json_encode($request->input(), JSON_UNESCAPED_UNICODE),
        ]);

        return $next($request);
    }
}

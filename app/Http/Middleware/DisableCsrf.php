<?php
// app/Http/Middleware/DisableCsrf.php

namespace App\Http\Middleware;

use Closure;

class DisableCsrf
{
    public function handle($request, Closure $next)
    {
        return $next($request)->withoutMiddleware(['VerifyCsrfToken']);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

final class RequestContext
{
    /** @param Closure(Request): Response $next */
    public function handle(Request $request, Closure $next): Response
    {
        $rid = $request->headers->get('X-Request-Id') ?: (string) Str::uuid();
        $start = microtime(true);

        Log::withContext([
            'request_id' => $rid,
            'ip' => $request->ip(),
            'method' => $request->method(),
            'path' => $request->path(),
        ]);

        /** @var Response $response */
        $response = $next($request);

        $duration = (int) round((microtime(true) - $start) * 1000);
        $response->headers->set('X-Request-Id', $rid);
        $response->headers->set('X-Response-Time', $duration.'ms');

        return $response;
    }
}

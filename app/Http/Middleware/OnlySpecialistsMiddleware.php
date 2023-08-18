<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnlySpecialistsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()?->isSpecialist()) {
            return $next($request);   
        } else {
            return response()->json(['errors' => ['message' => 'Forbidden']], Response::HTTP_FORBIDDEN);
        }
    }
}

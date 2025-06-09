<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info('CheckIfBanned middleware triggered for user: ' . (auth()->check() ? auth()->id() : 'guest'));

        if (auth()->check() && auth()->user()->profile && auth()->user()->profile->status === 'banned') {
            abort(403, 'You are banned.');
        }

        return $next($request);
    }
}

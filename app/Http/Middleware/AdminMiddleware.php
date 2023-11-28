<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(isset(Auth::user()->role_id) && Auth::user()->role_id === 1) {
            return $next($request);
        }

        throw new HttpResponseException(
            response()->json([
                "error" => [
                    "code" => 403,
                    "message" => "Forbidden for you"
                ]
            ], 403)
        );
    }
}

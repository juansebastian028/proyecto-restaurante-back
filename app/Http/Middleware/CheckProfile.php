<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $userProfile = $request->user()->profile()->first();
        if ($userProfile) {
            $request->request->add([
                'scope' => $userProfile->type
            ]);
        }

        return $next($request);
    }
}

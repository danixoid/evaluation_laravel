<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class AccessRole
{/**
 * @var \Illuminate\Contracts\Auth\Guard
 */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param Guard|\Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$role)
    {

        if ($this->auth->check() && $this->auth->user()->hasAnyRole($role)) {
            return $next($request);
        }

        abort(403);
    }
}

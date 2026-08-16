<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | Check Admin Login
        |--------------------------------------------------------------------------
        |
        | During login we stored:
        |
        | admin_user_id
        |
        | in the session.
        |
        */

        if (!session()->has('admin_user_id')) {

            /*
            |--------------------------------------------------------------------------
            | User is NOT logged in
            |--------------------------------------------------------------------------
            |
            | Send them to login page.
            |
            */

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Please login to access the admin panel.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | User is logged in
        |--------------------------------------------------------------------------
        |
        | Allow the request to continue.
        |
        */

        return $next($request);
    }
}
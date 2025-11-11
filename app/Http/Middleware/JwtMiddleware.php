<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class JwtMiddleware
{
   public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('token'); // now it works because of EncryptCookies except

        if (!$token) {
            return redirect()->route('login'); // no token → login
        }

        try {
            JWTAuth::setToken($token);
            $user = JWTAuth::authenticate();
        } catch (JWTException $e) {
            return redirect()->route('login'); // invalid/expired
        }

        return $next($request);
    }
}

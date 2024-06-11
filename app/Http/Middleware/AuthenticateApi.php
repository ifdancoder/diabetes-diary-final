<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->query('token');
        if(empty($token)){
            $token = $request->input('token');
        }
        if(empty($token)){
            $token = $request->bearerToken();
        }
        $credentials = explode(';', $token);
        $email = $credentials[0];
        $password = $credentials[1];
        $credentials = ['email' => $email, 'password' => $password];
        $result = auth()->attempt($credentials);
        if(!$result){
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth;
use Illuminate\Auth\Guard;
use App\User;

class AuthenticateGenelateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     public function __construct(Guard $auth) {
  		$this->auth = $auth;
  	 }

     public function handle($request, Closure $next) {
         if ($this->auth->user()->identification != User::IDENTIFICATION_GENERAL) {
             return redirect()->intended('/login');
         }
         return $next($request);
     }
}

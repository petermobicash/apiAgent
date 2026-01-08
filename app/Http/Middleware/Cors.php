<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
   

    public function handle($request, Closure $next) {    
     
        if ($request->isMethod('OPTIONS')) {
            $response = Response::make();
        } else {
            $response = $next($request);
        }
        return $response
            // ->header('Access-Control-Allow-Origin', 'http://127.0.0.1:300')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Accept,  X-Requested-With, Application');
    
 }
}
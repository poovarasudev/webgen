<?php

namespace App\Http\Middleware;

use App\Validators\ApiValidator;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResponseDecoratorMiddleware
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
        Validator::resolver(function ($translator, $data, $rules, $messages) {
            return new ApiValidator($translator, $data, $rules, $messages);
        });

        return $next($request);
    }
}

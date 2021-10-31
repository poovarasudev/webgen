<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
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
        $codeWithRouteName = 'AUTH-TOKEN-';
        if (!$this->auth->setRequest($request)->getToken()) {
            return commonErrorMessage(STATUS_CODE_UNAUTHORIZED, $codeWithRouteName . 'TOKEN_NOT_PROVIDED', 'Authorization Token Not Found.');
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenBlacklistedException $exception) {
            return commonErrorMessage(STATUS_CODE_UNAUTHORIZED, $codeWithRouteName . 'TOKEN_BLOCKLISTED', 'Authorization Token Blacklisted.');
        } catch (TokenExpiredException $exception) {
            return commonErrorMessage(STATUS_CODE_UNAUTHORIZED, $codeWithRouteName . 'TOKEN_EXPIRED', 'Authorization Token Expired.');
        } catch (JWTException $exception) {
            return commonErrorMessage(STATUS_CODE_UNAUTHORIZED, $codeWithRouteName . 'TOKEN_INVALID', 'Authorization Token Invalid.');
        } catch (ModelNotFoundException $exception) {
            return commonErrorMessage(STATUS_CODE_UNAUTHORIZED, $codeWithRouteName . 'USER_INVALID', 'Authorization Token Invalid User.');
        }

        if (!$user) {
            return commonErrorMessage(STATUS_CODE_NOT_FOUND, $codeWithRouteName . 'USER_NOT_FOUND', 'User Not Found.');
        }

        return $next($request);
    }
}

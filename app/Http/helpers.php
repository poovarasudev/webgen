<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * Handle an incoming request.
 *
 * @param $statusCode
 * @param $errorCode
 * @param $errorMessage
 * @return mixed
 */
function commonErrorMessage($statusCode, $errorCode, $errorMessage) {
    return response()->json(['error' => ['common_error' => ['code' => $errorCode, 'message' => $errorMessage]]], $statusCode);
}

/**
 * Get route name for error response.
 *
 * @return mixed
 */
function getRouteNameForError() {
    return strtoupper(Str::replace('.', '_', Route::currentRouteName()));
}

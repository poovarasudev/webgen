<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Authenticate the mobile app user.
     *
     * @param LoginRequest $request
     * return \Illuminate\Http\Response
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function authenticate(LoginRequest $request)
    {
        $input = $request->only(['email', 'password']);
        $codeWithRouteName = getRouteNameForError() . '-AUTHENTICATION-';

        try {
            $user = User::where('email', $input['email'])->first();
            if (empty($user) || !$token = JWTAuth::attempt($input)) {
                return commonErrorMessage(STATUS_CODE_UNAUTHORIZED, $codeWithRouteName . 'USER_DETAIL_ERROR', 'User Authentication Failed. Please Enter the Valid Login Credentials.');
            }

        } catch (ModelNotFoundException $exception) {
            Log::error("Error while Login", ["error" => ['message' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]]);
            return commonErrorMessage(STATUS_CODE_UNAUTHORIZED, $codeWithRouteName . 'ERROR', 'User Authentication Failed.');
        } catch (JWTException $exception) {
            Log::error("Error while Login", ["error" => ['message' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]]);
            return commonErrorMessage(STATUS_CODE_INTERNAL_ERROR, $codeWithRouteName . 'COULD_NOT_CREATE_TOKEN', 'There is Some Problem with Login. Please Login Again');
        }

        return response()->json((new UserTransformer)->transformForUserProfile($user, $token));
    }

    /**
     * Register a new User.
     *
     * @param RegisterRequest $request
     * return \Illuminate\Http\Response
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function register(RegisterRequest $request)
    {
        $input = $request->only(['name', 'email', 'password']);
        $codeWithRouteName = getRouteNameForError() . '-USER_REGISTRATION-';
        try {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);
            if (empty($user) || !$token = JWTAuth::attempt($input)) {
                return commonErrorMessage(STATUS_CODE_UNAUTHORIZED, $codeWithRouteName . 'USER_DETAIL_ERROR', 'User Authentication Failed. Please Enter the Valid Login Credentials.');
            }
        } catch (\Exception $exception) {
            Log::error("Error while registering a new", ["error" => ['message' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]]);
            return commonErrorMessage(STATUS_CODE_INTERNAL_ERROR, $codeWithRouteName . 'INTERNAL_ERROR', 'User Authentication Failed.');
        }

        return response()->json((new UserTransformer)->transformForUserProfile($user, $token));
    }

    /**
     * Get user details.
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function profile()
    {
        $codeWithRouteName = getRouteNameForError() . '-USER_DETAIL-';
        try {
            $user = auth()->user();
            if (!$user) {
                return commonErrorMessage(STATUS_CODE_UNAUTHORIZED, $codeWithRouteName . 'USER_DETAIL_ERROR', 'User Authentication Failed. Please Enter the Valid Login Credentials.');
            }
            return response()->json((new UserTransformer)->transformForUserProfile($user));
        } catch (\Exception $exception) {
            Log::error("Error while fetching profile", ["error" => ['message' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]]);
            return commonErrorMessage(STATUS_CODE_INTERNAL_ERROR, $codeWithRouteName . 'INTERNAL_ERROR', 'User Authentication Failed.');
        }
    }

    /**
     * Logout the user.
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function logout()
    {
        $codeWithRouteName = getRouteNameForError() . '-ERROR-';
        try {
            $token = JWTAuth::getToken();
            if ($token) {
                JWTAuth::invalidate($token);
            } else {
                return commonErrorMessage(STATUS_CODE_INTERNAL_ERROR, $codeWithRouteName . 'TOKEN_NOT_PROVIDED', 'Unable to logout, Internal error.');
            }
        } catch (\Exception $e) {
            Log::error("Error while logout", ["error" => ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]]);
            return commonErrorMessage(STATUS_CODE_INTERNAL_ERROR, $codeWithRouteName . 'INTERNAL_ERROR', 'Your Logout Request has been Failed. Please Try to Logout Again.');
        }
        return response()->json(['status' => 'success', 'message' => 'You are logout successfully.'], STATUS_CODE_SUCCESS);
    }
}

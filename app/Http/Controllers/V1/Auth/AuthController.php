<?php

namespace App\Http\Controllers\V1\Auth;

use Dingo\Api\Auth\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\User;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|confirmed',
        ]);

        try
        {
            $user = new User;
            $user->name= $request->input('username');
            $user->email= $request->input('email');
            $user->password = app('hash')->make($request->input('password'));
            $user->save();

            return response()->json( [
                'entity' => 'users',
                'action' => 'create',
                'result' => 'success'
            ], 201);

        }
        catch (\Exception $e)
        {
            return response()->json( [
                'entity' => 'users',
                'action' => 'create',
                'result' => 'failed'
            ], 409);
        }
    }
}

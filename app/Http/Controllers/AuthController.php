<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostLoginRequest;
use App\Http\Requests\PostRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(PostRegisterRequest $request)
    {
        $user = new User($request->validated());
        $user->remember_token = Str::random(10);
        $user->save();

        return new UserResource($user);
    }

    public function login(PostLoginRequest $request): JsonResource|JsonResponse
    {
        if (! Auth::attempt($request->validated())) {
            return response()->json([
                'success' => false,
                'message' => 'Could not login'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return new UserResource(Auth::user());
    }

    public function logout(Request $request): JsonResponse {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }
}

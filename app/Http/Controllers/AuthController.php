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

        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->createToken(now(), ['default'])->plainTextToken
        ]);
    }

    public function login(PostLoginRequest $request): JsonResource|JsonResponse
    {
        if (! Auth::attempt($request->validated())) {
            return response()->json([
                'success' => false,
                'message' => 'Wrong Credentials.'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Check if it's an administrator and issue him a token with admin ability
        $user = Auth::user();

        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->createToken(now(), [$user->isAdministrator() ? 'admin': 'default'])->plainTextToken
        ]);
    }

    public function logout(Request $request): JsonResponse {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }
}

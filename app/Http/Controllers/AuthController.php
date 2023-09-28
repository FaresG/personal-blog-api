<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostLoginRequest;
use App\Http\Requests\PostRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(PostRegisterRequest $request): JsonResource
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
            ]);
        }

        $request->session()->regenerate();
        return new UserResource(Auth::user());
    }
}

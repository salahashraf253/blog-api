<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);

        return response()->json([
            'data'=>new UserResource($user),
            'message' => 'User registered successfully',
        ], HttpResponse::HTTP_CREATED);
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $userCredentials = $request->validated();
        $user = User::where('email', $userCredentials['email'])->first();

        if (! $user || ! Hash::check($userCredentials['password'], $user->password)) {
            return response()->json(['error' => 'Unauthorized'], HttpResponse::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('api-token');

        return response()->json([
            'data' => [
                'access_token' => $token->plainTextToken,
            ],
            'message' => 'User logged in successfully',
        ], HttpResponse::HTTP_OK);
    }

    public function logout(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logged out successfully',
        ], HttpResponse::HTTP_OK);
    }
}
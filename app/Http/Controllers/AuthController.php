<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  public function register(RegisterRequest $request)
  {
    $validatedData = $request->validationData();
    $validatedData['password'] = bcrypt($validatedData['password']);

    $user = User::create($validatedData);

    return new UserResource($user);
  }

  public function login(LoginRequest $request)
  {
    $credentials = $request->only('username', 'password');

    if (!$token = Auth::attempt($credentials)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    $user = auth()->user();

    return response()->json(
      [
        'accessToken' => $token,
        // 'refreshToken' => '...',
        'user' => [
          'name' => $user->name,
        ],
      ]
    );
  }

  public function validateToken()
  {
    if (!auth()->check()) {
      return response()->noContent(401);
    }

    return response()->noContent(200);
  }
}

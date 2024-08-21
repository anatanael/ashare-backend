<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ValidateTokenRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

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
    $validatedData = $request->validationData();

    $username = $validatedData['username'];
    $password = $validatedData['password'];

    $token = Auth::attempt(['username' => $username, 'password' => $password]);

    if (!$token) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    $user = Auth::user();

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

  public function validateToken(ValidateTokenRequest $request)
  {
    $validatedData = $request->validationData();
    $token = $validatedData['token'];

    try {
      JWTAuth::setToken($token)->authenticate();
    } catch (TokenExpiredException $e) {
      return response()->noContent(401);
    } catch (JWTException $e) {
      return response()->noContent(401);
    }

    return response()->noContent(200);
  }
}

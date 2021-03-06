<?php

namespace App\Http\Controllers;

use App\Http\Contracts\IAuth as IAuthContractController;
use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseHelpers;
use App\Models\User;
use Dingo\Api\Http\Request;
use Validator;

class AuthController extends Controller implements IAuthContractController
{
  use ResponseHelpers;

  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('jwt.auth', ['except' => ['login', 'register']]);
    $this->middleware('auth:api', ['except' => ['login', 'register']]);
  }

  /**
   * Get a JWT via given credentials.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function login(Request $request)
  {
    $credentials = $request->only(['email', 'password']);
    $validator = Validator::make($credentials, [
      'email' => 'required|email',
      'password' => 'required',
    ]);

    if($validator->fails()){
      return $this->createValidationErrorResponse($validator);
    }
    if (! $token = auth()->attempt($credentials)) {
        return $this->response->errorUnauthorized();
    }

    return $this->respondWithToken($token, 'Login successfully.');
  }

  /**
   * Register an user
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function register(Request $request)
  {
    $credentials = $request->only(['email', 'password', 'confirm_password']);
    $validator = Validator::make($credentials, [
      'email' => 'required|email|unique:user,email',
      'password' => 'required'
    ]);
    if($validator->fails()){
      return $this->createValidationErrorResponse($validator);
    }

    $user = User::create(['email' => $credentials['email'], 'password' => app('hash')->make($credentials['password'])]);
    if (! $token = auth()->attempt($credentials)) {
      return $this->response->errorUnauthorized();
    }

    return $this->respondWithToken($token, 'Register successfully.');
  }

  /**
   * Get the authenticated User.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function me()
  {
    return $this->createResponse(auth()->user(), 'Get user profile.');
  }

  /**
   * Log the user out (Invalidate the token).
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout()
  {
    auth()->logout();

    return $this->createResponse(null, 'Successfully logged out.');
  }

  /**
   * Refresh a token.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function refresh()
  {
    // auth()->invalidate();
    return $this->respondWithToken(auth()->refresh(true), 'Token refresh successfully.');
  }

  /**
   * Get the token array structure.
   *
   * @param  string $token
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function respondWithToken(string $token, string $message)
  {
    return $this->createResponse([
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => auth()->factory()->getTTL() * 60
    ], $message);
  }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseHelpers;
use Dingo\Api\Http\Request;
use Validator;

class AuthController extends Controller
{
  use ResponseHelpers;

  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login']]);
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
    return $this->respondWithToken(auth()->refresh(), 'Token refresh successfully.');
  }

  /**
   * Get the token array structure.
   *
   * @param  string $token
   *
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondWithToken($token, $message)
  {
    return $this->createResponse([
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => auth()->factory()->getTTL() * 60
    ], $message);
  }
}

<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Contracts\IAuth as IAuthContractController;
use App\Http\Controllers\Controller;
use App\Http\Traits\AuthAdmin;
use App\Http\Traits\ResponseHelpers;
use App\Models\UserAdmin;
use Dingo\Api\Http\Request;
use Validator;

class AuthController extends Controller implements IAuthContractController
{
  use ResponseHelpers, AuthAdmin;

  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->setGuard();
    $this->middleware("auth:{$this->guard}", ['except' => ['login']]);
  }

  private function setGuard()
  {
    $this->guard = env('AUTH_GUARD_DASHBOARD', 'dashboard');
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
    if (! $token = $this->authAdmin()->attempt($credentials)) {
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
    return $this->createResponse($this->authAdmin()->user(), 'Get user profile.');
  }

  /**
   * Log the user out (Invalidate the token).
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout()
  {
    $this->authAdmin()->logout();

    return $this->createResponse(null, 'Successfully logged out.');
  }

  /**
   * Refresh a token.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function refresh()
  {
    return $this->respondWithToken($this->authAdmin()->refresh(), 'Token refresh successfully.');
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
      'expires_in' => $this->authAdmin()->factory()->getTTL() * 60
    ], $message);
  }
}

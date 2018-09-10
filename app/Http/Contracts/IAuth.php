<?php
namespace App\Http\Contracts;

use Dingo\Api\Http\Request;

interface IAuth{
  public function login(Request $request);
  public function me();
  public function logout();
  public function refresh();
  public function respondWithToken(string $token, string $message);
}
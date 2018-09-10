<?php

use Faker\Factory as Faker;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth;

class DashAuthControllerTest extends TestCase
{

  
  /**
   * Guset cannot see their detail profiles without their token.
   * @author PW <mbapewe@gmail.com>
   * @group DashboardTest
   * @return void
   */
  public function testGuestCannotSeeTherProfileWithoutToken()
  {
    $this->get('/dashs/auth/me')
      ->seeJson([
        'message' => "Token not provided",
        'status_code' => 401
      ])
      ->seeJsonStructure([
        'error' => [
          'message',
          'status_code'
        ]
      ])
      ->seeStatusCode(401);
  }


  /**
   * User as admin can get their profile details successfully.
   * @author PW <mbapewe@gmail.com>
   * @group DashboardTest
   * @return void
   */
  public function testAdminCanGetTheirProfileSuccessfully()
  {
    $user = App\Models\UserAdmin::where('email', 'user_test@company.com')->firstOrFail();
    $token = JWTAuth::fromUser($user);
    $this->get('/dashs/auth/me', ['Authorization' => "Bearer {$token}"])
      ->seeJson([
        'email' => $user->email,
        'message' => "Get user profile.",
        'status_code' => 200
      ])
      ->seeJsonStructure([
        'data',
        'message',
        'status_code'
      ])
      ->assertResponseOk();
  }

  /**
   * Guest cannot logout.
   * @author PW <mbapewe@gmail.com>
   * @group DashboardTest
   * @return void
   */
  public function testGuestCannotLogout()
  {
    $this->post('/dashs/auth/logout')
      ->seeJson([
        'message' => "Token not provided",
        'status_code' => 401
      ])
      ->seeJsonStructure([
        'error' => [
          'message',
          'status_code'
        ]
      ])
      ->seeStatusCode(401);
  }

  /**
   * User as Admin can logout successfully.
   * @author PW <mbapewe@gmail.com>
   * @group DashboardTest
   * @return void
   */
  public function testAdminCanLogoutSuccessfully()
  {
    $user = App\Models\UserAdmin::where('email', 'user_test@company.com')->firstOrFail();
    $token = JWTAuth::fromUser($user);
    $this->post('/dashs/auth/logout', [], ['Authorization' => "Bearer {$token}"])
      ->seeJson([
        'message' => "Successfully logged out.",
        'status_code' => 200
      ])
      ->seeJsonStructure([
        'message',
        'status_code'
      ])
      ->assertResponseOk();
  }


  /**
   * Guest cannot refresh their token.
   * @author PW <mbapewe@gmail.com>
   * @group DashboardTest
   * @return void
   */
  public function testGuestCannotRefreshTheirToken()
  {
    $this->post('/dashs/auth/refresh')
      ->seeJson([
        'message' => "Token not provided",
        'status_code' => 401
      ])
      ->seeJsonStructure([
        'error' => [
          'message',
          'status_code'
        ]
      ])
      ->seeStatusCode(401);
  }

  /**
   * User as admin can refresh their token successfully.
   * @author PW <mbapewe@gmail.com>
   * @group DashboardTest
   * @return void
   */
  public function testAdminCanRefreshTheirTokenSuccessfully()
  {
    $user = App\Models\UserAdmin::where('email', 'user_test@company.com')->firstOrFail();
    $token = JWTAuth::fromUser($user);
    $this->post('/dashs/auth/refresh', [], ['Authorization' => "Bearer {$token}"])
      ->seeJson([
        'message' => "Token refresh successfully.",
        'status_code' => 200
      ])
      ->seeJsonStructure([
        'message',
        'status_code'
      ])
      ->assertResponseOk();
  }

  /**
   * User as Admin can login because incomplete form.
   * @author PW <mbapewe@gmail.com>
   * @group DashboardTest
   * @return void
   */
  public function testAdminCannotLoginInCompleteForm()
  {
    $user = App\Models\UserAdmin::where('email', 'user_test@company.com')->firstOrFail();
    $payloads = [
      'email' => $user->email,
    ];
    $this->post('/dashs/auth/login', $payloads)
      ->seeJson([
        'message' => "422 Unprocessable Entity",
        'status_code' => 422
      ])
      ->seeJsonStructure([
        'error' => [
          'errors',
          'message',
          'status_code'
        ]
      ])
      ->seeStatusCode(422);
  }

  /**
   * User as Admin can login successfully.
   * @author PW <mbapewe@gmail.com>
   * @group DashboardTest
   * @return string 
   */
  public function testAdminCanLoginSuccessfully()
  {
    $user = App\Models\UserAdmin::where('email', 'user_test@company.com')->firstOrFail();
    $payloads = [
      'email' => $user->email,
      'password' => "secret"
    ];
    $this->post('/dashs/auth/login', $payloads)
      ->seeJson([
        'message' => "Login successfully.",
        'status_code' => 200
      ])
      ->seeJsonStructure([
        'data' => ['access_token', 'expires_in', 'token_type'],
        'message',
        'status_code'
      ])
      ->assertResponseOk();
    $response = json_decode($this->response->getContent());
    return $response->data->access_token;
  }

  /**
   * User as Admin can use their token after logged.
   * @author PW <mbapewe@gmail.com>
   * @group DashboardTest
   * @depends testAdminCanLoginSuccessfully
   * @return string
   */
  public function testAdminCanUseTheirTokenAfterLogged($token)
  {
    $this->get('/dashs/auth/me', ['Authorization' => "Bearer {$token}"])
      ->seeJson([
        'message' => "Get user profile.",
        'status_code' => 200
      ])
      ->seeJsonStructure([
        'data',
        'message',
        'status_code'
      ])
      ->assertResponseOk();
    return $token;
  }

  /**
   * User as Admin can logout with their token after logged successfully.
   * @author PW <mbapewe@gmail.com>
   * @group DashboardTest
   * @depends testAdminCanUseTheirTokenAfterLogged
   * @return string
   */
  public function testAdminCanLogoutWithTheirTokenAfterLoggedSuccessfully($token)
  {
    $this->post('/dashs/auth/logout', [], ['Authorization' => "Bearer {$token}"])
      ->seeJson([
        'message' => "Successfully logged out.",
        'status_code' => 200
      ])
      ->seeJsonStructure([
        'message',
        'status_code'
      ])
      ->assertResponseOk();
    return $token;
  }
}

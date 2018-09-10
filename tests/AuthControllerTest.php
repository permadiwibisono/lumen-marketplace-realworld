<?php

use Faker\Factory as Faker;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerTest extends TestCase
{

  /**
   * Guest can register successfully.
   * @author PW <mbapewe@gmail.com>
   * @group MarketplaceTest
   * @return void
   */
  public function testGuestCanRegisterSuccessfully()
  {
    $faker = Faker::create();
    $email = $faker->email;
    $payloads = [
      'email' => $email,
      'password' => $faker->password
    ];
    $this->post('/auth/register', $payloads)
      ->seeJson([
        'message' => "Register successfully.",
        'status_code' => 200
      ])
      ->seeJsonStructure([
        'data' => ['access_token', 'expires_in', 'token_type'],
        'message',
        'status_code'
      ])
      ->assertResponseOk();
    $this->seeInDatabase('user', ['email' => $email]);
  }

  /**
   * Guest cannot register because incomplete form.
   * @author PW <mbapewe@gmail.com>
   * @group MarketplaceTest
   * @return void
   */
  public function testGuestCannotRegisterInCompleteForm()
  {
    $faker = Faker::create();
    $email = $faker->email;
    $payloads = [
      'email' => $email
    ];
    $this->post('/auth/register', $payloads)
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
    $this->notSeeInDatabase('user', ['email' => $email]);
  }

  /**
   * Guest cannot register because with the same email.
   * @author PW <mbapewe@gmail.com>
   * @group MarketplaceTest
   * @return void
   */
  public function testGuestCannotRegisterWithTheSameEmail()
  {
    $faker = Faker::create();
    $email = $faker->email;
    $payloads = [
      'email' => 'user_test@company.com',
      'password' => 'secret'
    ];
    $this->post('/auth/register', $payloads)
      ->seeJson([
        'errors' => [
          'email' => ["The email has already been taken."]
        ],
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
    $this->notSeeInDatabase('user', ['email' => $email]);
  }
  
  /**
   * Guset cannot see their detail profiles without their token.
   * @author PW <mbapewe@gmail.com>
   * @group MarketplaceTest
   * @return void
   */
  public function testGuestCannotSeeTherProfileWithoutToken()
  {
    $this->get('/auth/me')
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
   * User can get their profile details successfully.
   * @author PW <mbapewe@gmail.com>
   * @group MarketplaceTest
   * @return void
   */
  public function testUserCanGetTheirProfileSuccessfully()
  {
    $user = factory(App\Models\User::class)->create();
    $this->seeInDatabase('user', ['id' => $user->id, 'email' => $user->email]);
    $token = JWTAuth::fromUser($user);
    $this->get('/auth/me', ['Authorization' => "Bearer {$token}"])
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
   * @group MarketplaceTest
   * @return void
   */
  public function testGuestCannotLogout()
  {
    $this->post('auth/logout')
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
   * User can logout successfully.
   * @author PW <mbapewe@gmail.com>
   * @group MarketplaceTest
   * @return void
   */
  public function testUserCanLogoutSuccessfully()
  {
    $user = factory(App\Models\User::class)->create();
    $this->seeInDatabase('user', ['id' => $user->id, 'email' => $user->email]);
    $token = JWTAuth::fromUser($user);
    $this->post('auth/logout', [], ['Authorization' => "Bearer {$token}"])
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
   * @group MarketplaceTest
   * @return void
   */
  public function testGuestCannotRefreshTheirToken()
  {
    $this->post('/auth/refresh')
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
   * User can refresh their token successfully.
   * @author PW <mbapewe@gmail.com>
   * @group MarketplaceTest
   * @return void
   */
  public function testUserCanRefreshTheirTokenSuccessfully()
  {
    $user = factory(App\Models\User::class)->create();
    $this->seeInDatabase('user', ['id' => $user->id, 'email' => $user->email]);
    $token = JWTAuth::fromUser($user);
    $this->post('/auth/refresh', [], ['Authorization' => "Bearer {$token}"])
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
   * User can login because incomplete form.
   * @author PW <mbapewe@gmail.com>
   * @group MarketplaceTest
   * @return void
   */
  public function testUserCannotLoginInCompleteForm()
  {
    $user = factory(App\Models\User::class)->create();
    $this->seeInDatabase('user', ['id' => $user->id, 'email' => $user->email]);
    $payloads = [
      'email' => $user->email,
    ];
    $this->post('/auth/login', $payloads)
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
   * User can login successfully.
   * @author PW <mbapewe@gmail.com>
   * @group MarketplaceTest
   * @return string 
   */
  public function testUserCanLoginSuccessfully()
  {
    $user = App\Models\User::where('email', 'user_test@company.com')->firstOrFail();
    $payloads = [
      'email' => $user->email,
      'password' => "secret"
    ];
    $this->post('/auth/login', $payloads)
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
   * User can use their token after logged.
   * @author PW <mbapewe@gmail.com>
   * @group MarketplaceTest
   * @depends testUserCanLoginSuccessfully
   * @return string
   */
  public function testUserCanUseTheirTokenAfterLogged($token)
  {
    $this->get('/auth/me', ['Authorization' => "Bearer {$token}"])
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
   * User can logout with their token after logged successfully.
   * @author PW <mbapewe@gmail.com>
   * @group MarketplaceTest
   * @depends testUserCanUseTheirTokenAfterLogged
   * @return string
   */
  public function testUserCanLogoutWithTheirTokenAfterLoggedSuccessfully($token)
  {
    $this->post('auth/logout', [], ['Authorization' => "Bearer {$token}"])
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

  // /**
  //  * User cannot use the old token after logout.
  //  * @author PW <mbapewe@gmail.com>
  //  * @group MarketplaceTest
  //  * @depends testUserCanLogoutWithTheirTokenAfterLoggedSuccessfully
  //  * @return void
  //  */
  // public function testUserCannotUseTokenAfterLogout($token)
  // {
  //   $this->get('/auth/me', ['Authorization' => "Bearer {$token}"])
  //     ->seeJson([
  //       'message' => "The token has been blacklisted",
  //       'status_code' => 401
  //     ])
  //     ->seeJsonStructure([
  //       'error' => [
  //         'message',
  //         'status_code'
  //       ]
  //     ])
  //     ->seeStatusCode(401);
  // }
  
  /**
   * Guest can use their access token after registered.
   * @author PW <mbapewe@gmail.com>
   * @group MarketplaceTest
   * @depends testGuestCanRegisterSuccessfully
   * @return void
   */
  // public function testGuestCanUseTheirAccessTokenAfterRegistered(string $access_token)
  // {
  //   $faker = Faker::create();
  //   $email = $faker->email;
  //   $payloads = [
  //     'email' => $email,
  //     'password' => $faker->password
  //   ];
  //   $this->json('POST', '/auth/register', $payloads)
  //     ->seeJson([
  //       'message' => "Register successfully.",
  //       'status_code' => 200
  //     ])
  //     ->seeJsonStructure([
  //       'data' => ['access_token', 'expires_in', 'token_type'],
  //       'message',
  //       'status_code'
  //     ])
  //     ->assertResponseOk();
  //   $response = json_decode($this->response->getContent());
  //   $access_token = $response->data->access_token;
  //   $this->seeInDatabase('user', ['email' => $email]);
  //   $headers = ['Authorization' => "Bearer $access_token"];    
  //   $this->json('GET', '/auth/me', [], $headers)
  //     ->seeJson([
  //       'email' => $email,
  //       'message' => "Get user profile.",
  //       'status_code' => 200
  //     ])
  //     ->seeJsonStructure([
  //       'data',
  //       'message',
  //       'status_code'
  //     ])
  //     ->assertResponseOk();
  // }
}

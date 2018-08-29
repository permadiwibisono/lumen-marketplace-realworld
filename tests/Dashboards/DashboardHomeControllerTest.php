<?php

class DashboardHomeControllerTest extends TestCase
{
  /**
   * Admin cannot get access home dashboard API without login.
   * @author PW <mbapewe@gmail.com>
   * @group DashboardTest
   * @return void
   */
  public function testAdminCannotGetHomeDashboardApiWithoutLogin()
  {
    $this->json('GET', '/dashs')
      ->seeJson([
        'message' => "Unauthorized",
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
   * User cannot get access home dashboard API with their credentials.
   * @author PW <mbapewe@gmail.com>
   * @group DashboardTest
   * @return void
   */
  public function testUserCannotGetHomeDashboardApi()
  {
    $user = factory(App\Models\User::class)->create();
    $this->actingAs($user)
      ->json('GET', '/dashs')
      ->seeJson([
        'message' => "Unauthorized",
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
   * Admin can get access home dashboard API successfully.
   * @author PW <mbapewe@gmail.com>
   * @group DashboardTest
   * @return void
   */
  public function testAdminCanGetHomeDashboardApiSuccessfully()
  {
    $admin = factory(App\Models\UserAdmin::class)->create();
    $this->actingAs($admin, 'dashboard')
      ->json('GET', '/dashs')
      ->seeJsonEquals([
        'data' => [ 
          'framework' => $this->app->version()
        ],
        'message' => "Marketplace's Dashboard API.",
        'status_code' => 200
      ])
      ->seeJsonStructure([
        'data' => [ 'framework' ],
        'message',
        'status_code'
      ])
      ->assertResponseOk();
  }
}

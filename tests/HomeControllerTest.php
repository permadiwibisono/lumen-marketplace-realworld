<?php

class HomeControllerTest extends TestCase
{
  /**
   * User can get access public API successfully.
   * @author PW <mbapewe@gmail.com>
   * @group MarketplaceTest
   * @return void
   */
  public function testUserGetPublicApiSuccessfully()
  {
    $this->json('GET', '/')
      ->seeJsonEquals([
        'data' => [ 
          'framework' => $this->app->version()
        ],
        'message' => "Marketplace's API.",
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

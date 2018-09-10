<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Dashboards\BaseController;
use App\Http\Traits\ResponseHelpers;

class HomeController extends BaseController
{
  use ResponseHelpers;

  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
    $this->middleware($this->authMiddleware);
  }

  /**
   * Get details API like framework.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index()
  {
    return $this->createResponse(['framework' => app()->version()], 'Marketplace\'s Dashboard API.');
  }

}

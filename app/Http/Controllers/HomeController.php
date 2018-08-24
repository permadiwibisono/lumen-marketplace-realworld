<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseHelpers;

class HomeController extends Controller
{
  use ResponseHelpers;
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      //
  }

  public function index()
  {
    return $this->createResponse(['framework' => app()->version()], 'Marketplace\'s API.');
  }
}

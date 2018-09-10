<?php
namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use App\Http\Traits\AuthAdmin;

/**
 * Base Controller for Dashboard's routes.
 */
class BaseController extends Controller
{
  use AuthAdmin;
  
  protected $authMiddleware;

  function __construct()
  {
    $this->setGuard();
    $this->authMiddleware = "auth:{$this->guard}";
  }
}
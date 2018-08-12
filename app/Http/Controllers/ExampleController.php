<?php

namespace App\Http\Controllers;

class ExampleController extends Controller
{
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
    return response()->json(['message'=>'Hello World!','status_code'=>200],200);
  }
}

<?php

namespace App\Http\Traits;

use Dingo\Api\Routing\Helpers;
use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Validation\Validator;

trait ResponseHelpers{
  use Helpers;

  public function createResponse($data = null, $message = 'Success!', $status_code = 200)
  {
    $dataArray = array();
    if(!is_null($data))
      $dataArray['data'] = $data;
    $dataArray = array_merge($dataArray, [ 'message' => $message,'status_code' => $status_code ]);
    return $this->response->array($dataArray)->setStatusCode($status_code);
  }

  public function createValidationErrorResponse(Validator $validator)
  {
    throw new ValidationHttpException($validator->errors());
  }
}

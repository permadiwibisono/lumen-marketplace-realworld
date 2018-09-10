<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEmailToken extends Model
{
  protected $guarded = [];

  protected $table = 'user_email_token';

  public function user()
  {
    return $this->morphTo(null, 'type', 'user_id');
  }
}

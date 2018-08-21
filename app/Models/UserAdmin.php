<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAdmin extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
  use Authenticatable, Authorizable, SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $guarded = [];

  protected $table = 'user_admin';

  protected $dates = ['birth_at', 'deleted_at', 'created_at', 'updated_at'];

  protected $casts = [
    'is_verified_email' => 'boolean',
    'is_suspended' => 'boolean'
  ];

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = [
    'password'
  ];

  public function emailToken()
  {
    return $this->morphOne('App\Models\UserEmailToken', 'user', 'type', 'user_id'); 
  }
  
  /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  /**
   * Return a key value array, containing any custom claims to be added to the JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
    return [
      "email" => $this->attributes['email']
    ];
  }

}

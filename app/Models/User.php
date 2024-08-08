<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
  use Notifiable;

  protected $fillable = ['id', 'name', 'username', 'password', 'email', 'url_image'];

  protected $hidden = [
    'password',
  ];

  public function categories()
  {
    return $this->hasMany(Category::class);
  }

  public function notes()
  {
    return $this->hasMany(Note::class);
  }

  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  public function getJWTCustomClaims()
  {
    return [];
  }
}

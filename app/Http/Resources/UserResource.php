<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id'        => $this->id,
      'name'      => $this->name,
      'username'  => $this->username,
      'email'     => $this->email,
      'urlImage'  => $this->url_image,
      'createdAt' => $this->created_at->toDateTimeString(),
      'updatedAt' => $this->updated_at->toDateTimeString(),
    ];
  }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id'              => $this->id,
      'title'           => $this->title,
      'urlImage'        => $this->url_image,
      'hashDeleteImage' => $this->hash_delete_image,
      'createdAt'       => $this->created_at->toDateTimeString(),
      'updatedAt'       => $this->updated_at->toDateTimeString(),
    ];
  }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id'         => $this->id,
      'text'       => $this->text,
      'categoryId' => $this->category_id,
      'createdAt'  => $this->created_at->toDateTimeString(),
      'updatedAt'  => $this->updated_at->toDateTimeString(),
    ];
  }
}

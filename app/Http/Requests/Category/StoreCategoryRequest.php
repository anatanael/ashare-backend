<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
  public function authorize(): bool
  {
    return auth()->check();
  }

  public function rules(): array
  {
    return [
      'title'             => 'required|string|max:20',
      'url_picture'       => 'nullable|string|url|max:255',
      'hash_delete_image' => 'nullable|string|max:255',
    ];
  }
}

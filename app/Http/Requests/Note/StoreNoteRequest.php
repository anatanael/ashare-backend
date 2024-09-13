<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreNoteRequest extends FormRequest
{
  public function authorize(): bool
  {
    return Auth::check();
  }

  public function rules(): array
  {
    return [
      'text'       => 'required|string|max:65635',
      'categoryId' => 'required|exists:categories,id',
    ];
  }

  protected function prepareForValidation()
  {
    $this->merge([
      'category_id' => $this->categoryId,
    ]);
  }
}

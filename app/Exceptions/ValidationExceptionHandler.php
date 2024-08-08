<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ValidationExceptionHandler extends Exception
{
  public static function handle(ValidationException $e, Request $request)
  {
    return response()->json([
      'status' => 'FAILED',
      'errors' => $e->errors(),
    ], 422);
  }
}

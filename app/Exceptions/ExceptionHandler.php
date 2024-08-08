<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ExceptionHandler extends Exception
{
  public static function handle(Exceptions $exceptions)
  {
    $exceptions->renderable(function (Exception $e, Request $request) {
      if ($e instanceof ValidationException) {
        return ValidationExceptionHandler::handle($e, $request);
      }

      return null;
    });
  }
}

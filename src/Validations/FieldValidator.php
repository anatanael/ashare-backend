<?php

namespace AshareApp\Validations;

class FieldValidator
{
  public static function checkRequiredFields($fields, $requiredFields)
  {
    if (!$fields) {
      $fields = [];
    }

    $missingFields = [];

    foreach ($requiredFields as $field => $errorMessage) {
      if (is_numeric($field)) {
        $field = $errorMessage;
        $errorMessage = "'$field' is required";
      }

      if (empty($fields[$field])) {
        $missingFields[] = $errorMessage;
      } elseif (!array_key_exists($field, $fields)) {
        $missingFields[] = $errorMessage;
      }
    }

    if (!empty($missingFields)) {
      $errorMessage = json_encode(
        ['errors' => $missingFields]
      );
      throw new FieldValidationException($errorMessage);
    }
  }
}

class FieldValidationException extends \Exception
{
  public function __construct($message = '', $code = 0, \Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}

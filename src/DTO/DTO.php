<?php

namespace AshareApp\DTO;

use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;

class DTO implements JsonSerializable
{
  public function jsonSerialize()
  {
    $reflection = new ReflectionClass($this);
    $props = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);

    $result = [];
    foreach ($props as $prop) {
      $prop->setAccessible(true);
      $result[$prop->getName()] = $prop->getValue($this);
    }

    return $result;
  }
}

<?php

declare(strict_types=1);

namespace Drupal\server_general\ThemeTrait\Enum;

/**
 * Enum for buttons in Person Card.
 */
enum PersonCardButtonEnum: string {
  case Email = 'email';
  case Call = 'call';
  case Website = 'website';
}

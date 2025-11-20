<?php

declare(strict_types=1);

namespace Drupal\server_general\ThemeTrait;

use Drupal\server_general\ThemeTrait\Enum\AlignmentEnum;

/**
 * Helper methods for rendering Card elements.
 */
trait CardThemeTrait {

  use ElementLayoutThemeTrait;

  /**
   * Wrap multiple cards with a grid.
   *
   * @param array $items
   *   The elements as render array.
   *
   * @return array
   *   Render array.
   */
  protected function buildCards(array $items): array {
    return [
      '#theme' => 'server_theme_cards',
      '#items' => $items,
    ];
  }

  /**
   * Build "Centered card" layout with buttons at the bottom.
   *
   * @param array $items
   *   The elements as render array.
   * @param array $buttons
   *   The elements as render array.
   *
   * @return array
   *   Render array.
   */
  protected function buildInnerCardsLayoutWithButtons(array $items, array $buttons): array {
    return [
      '#theme' => 'server_theme_inner_cards_layout__with_buttons',
      '#items' => $this->wrapContainerVerticalSpacing($items, AlignmentEnum::Center),
      '#buttons' => $buttons,
    ];
  }

}

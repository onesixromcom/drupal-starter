<?php

declare(strict_types=1);

namespace Drupal\server_general\ThemeTrait;

use Drupal\server_general\ThemeTrait\Enum\FontSizeEnum;
use Drupal\server_general\ThemeTrait\Enum\AlignmentEnum;
use Drupal\server_general\ThemeTrait\Enum\FontWeightEnum;
use Drupal\server_general\ThemeTrait\Enum\TextColorEnum;
use Drupal\server_general\ThemeTrait\Enum\PersonCardButtonEnum;

/**
 * Helper methods for rendering Quick Links elements.
 */
trait PersonCardThemeTrait {

  /**
   * Build People cards element.
   *
   * @param string $title
   *   The title.
   * @param array $body
   *   The body render array.
   * @param array $items
   *   The render array built with
   *   `ElementLayoutThemeTrait::buildElementLayoutTitleBodyAndItems`.
   *
   * @return array
   *   The render array.
   */
  protected function buildElementPersonCardTeasers(string $title, array $body, array $items): array {
    return $this->buildElementLayoutTitleBodyAndItems(
      $title,
      $body,
      $this->buildPersonsCards($items),
    );
  }

  /**
   * Wrap multiple cards with a grid.
   *
   * @param array $items
   *   The elements as render array.
   *
   * @return array
   *   Render array.
   */
  protected function buildPersonsCards(array $items): array {
    return [
      '#theme' => 'server_theme_persons_cards',
      '#items' => $items,
    ];
  }

  /**
   * Build "Centered card" layout with buttons at the bottom.
   *
   * @param array $items
   *   The elements as render array.
   *
   * @return array
   *   Render array.
   */
  protected function buildInnerCardsLayoutWithButtons(array $items): array {
    return [
      '#theme' => 'server_theme_inner_cards_layout__with_buttons',
      '#items' => $items,
    ];
  }

  /**
   * Builds a Quick Link element.
   *
   * @param string $name
   *   The name.
   * @param string $image_url
   *   The image url.
   * @param string $position
   *   The position.
   * @param string $role
   *   Optional; The role.
   *
   * @return array
   *   Render array.
   */
  protected function buildElementPersonCardItem(string $name, string $image_url, string $position, string $role = ''): array {
    $elements = [];
    $element = [
      '#theme' => 'image',
      '#uri' => $image_url,
      '#alt' => $name,
      '#width' => 100,
    ];

    $elements[] = $this->wrapRoundedCornersFull($element);

    $inner_elements = [];

    $element = $this->wrapTextFontWeight($name, FontWeightEnum::Bold);
    $inner_elements[] = $this->wrapTextCenter($element);

    $element = $this->wrapTextResponsiveFontSize($position, FontSizeEnum::Sm);
    $element = $this->wrapTextCenter($element);
    $inner_elements[] = $this->wrapTextColor($element, TextColorEnum::Gray);

    if (!empty($role)) {
      $element = $this->wrapTextResponsiveFontSize($role, FontSizeEnum::Sm);
      $element = $this->wrapTextCenter($element);
      $inner_elements[] = $this->wrapBadge($element, TextColorEnum::DarkGreen, TextColorEnum::LightGreen);
    }

    $elements[] = $this->wrapContainerVerticalSpacingTiny($inner_elements, AlignmentEnum::Center);

    $buttons_inner = [];
    for ($i = 0; $i < rand(1, 2); $i++) {
      switch (rand(0, 2)) {
        case 0:
          $buttons_inner[] = $this->buildContactButton('myemail@somedomain.com', PersonCardButtonEnum::Email);
          break;

        case 1:
          $buttons_inner[] = $this->buildContactButton('+380991234567', PersonCardButtonEnum::Call);
          break;

        case 2:
          $buttons_inner[] = $this->buildContactButton('https://example.com', PersonCardButtonEnum::Website);
          break;
      }
    }

    $elements[] = $this->buildContactButtonsHelper($buttons_inner);

    return $this->buildInnerCardsLayoutWithButtons($elements);
  }

  /**
   * Build buttons.
   *
   * @param array $items
   *   The elements as render array.
   *
   * @return array
   *   The rendered button array.
   */
  private function buildContactButtonsHelper(array $items): array {
    return [
      '#theme' => 'server_theme_element__person_card_buttons',
      '#items' => $items,
    ];
  }

  /**
   * Build a button.
   *
   * @param string $info
   *   The info for the link.
   * @param \Drupal\server_general\ThemeTrait\Enum\PersonCardButtonEnum $button_type
   *   Type of the button.
   *
   * @return array
   *   The rendered button array.
   */
  private function buildContactButton(string $info, PersonCardButtonEnum $button_type = PersonCardButtonEnum::Email): array {
    return [
      '#theme' => 'server_theme_element__person_card_button',
      '#button_type' => $button_type->value,
      '#info' => $info,
    ];
  }

}

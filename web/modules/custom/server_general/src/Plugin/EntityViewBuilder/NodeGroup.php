<?php

namespace Drupal\server_general\Plugin\EntityViewBuilder;

use Drupal\Core\Url;
use Drupal\node\NodeInterface;
use Drupal\og\OgMembershipInterface;
use Drupal\server_general\EntityViewBuilder\NodeViewBuilderAbstract;
use Drupal\server_general\ProcessedTextBuilderTrait;
use Drupal\server_general\ThemeTrait\ElementWrapThemeTrait;
use Drupal\server_general\ThemeTrait\TitleAndLabelsThemeTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The "Node Group" plugin.
 *
 * @EntityViewBuilder(
 *   id = "node.group",
 *   label = @Translation("Node - Group"),
 *   description = "Node view builder for Group bundle."
 * )
 */
class NodeGroup extends NodeViewBuilderAbstract {

  use TitleAndLabelsThemeTrait;
  use ProcessedTextBuilderTrait;
  use ElementWrapThemeTrait;

  /**
   * The og access.
   *
   * @var \Drupal\og\OgAccessInterface
   */
  protected $ogAccess;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $plugin = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $plugin->ogAccess = $container->get('og.access');

    return $plugin;
  }

  /**
   * Build full view mode.
   *
   * @param array $build
   *   The existing build.
   * @param \Drupal\node\NodeInterface $entity
   *   The entity.
   *
   * @return array
   *   Render array.
   */
  public function buildFull(array $build, NodeInterface $entity) {
    $element[] = $this->buildPageTitle($entity->label());
    $element[] = $this->buildProcessedText($entity, 'body');

    /** @var \Drupal\Core\Session\AccountInterface $account */
    $account = $this->currentUser->getAccount();

    if (!$account->isAnonymous()) {
      $access_result = $this->ogAccess->userAccessEntityOperation('update', $entity, $account);
      if ($access_result->isNeutral()) {

        $parameters = [
          'entity_type_id' => $entity->getEntityTypeId(),
          'group' => $entity->id(),
          'og_membership_type' => OgMembershipInterface::TYPE_DEFAULT,
        ];

        $element[] = [
          '#theme' => 'server_theme_user_greeting',
          '#name' => $account->getAccountName() ?? $account->getDisplayName(),
          '#link' => [
            '#type' => 'link',
            '#title' => 'click here',
            '#url' => Url::fromRoute('og.subscribe', $parameters),
          ],
          '#label' => $entity->label(),
        ];
      }
    }

    $elements[] = $this->wrapContainerWide($element);

    $elements = $this->wrapContainerVerticalSpacingBig($elements);

    $build[] = $this->wrapContainerBottomPadding($elements);

    return $build;
  }

}

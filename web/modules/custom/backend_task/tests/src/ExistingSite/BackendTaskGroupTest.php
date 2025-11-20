<?php

namespace Drupal\Tests\backend_task;

use Symfony\Component\HttpFoundation\Response;
use Drupal\Tests\server_general\ExistingSite\ServerGeneralNodeTestBase;

/**
 * Test 'group' content type.
 */
class BackendTaskGroupTest extends ServerGeneralNodeTestBase {

  /**
   * {@inheritdoc}
   */
  public function getEntityBundle(): string {
    return 'group';
  }

  /**
   * {@inheritdoc}
   */
  public function getRequiredFields(): array {
    return [
      'body',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionalFields(): array {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function testOgGroupVisibility() {
    $adminUser = $this->createUser([], 'SuperAdmin', TRUE);

    // Create News node with image.
    $node = $this->createNode([
      'title' => 'Test Group',
      'type' => 'group',
      'uid' => $adminUser->id(),
      'body' => 'This is the text of the body field.',
      'moderation_state' => 'published',
    ]);
    $node->save();

    // Anonmymous don't see the message.
    $this->drupalGet($node->toUrl());
    $this->assertSession()->statusCodeEquals(Response::HTTP_OK);
    $this->assertSession()->elementNotExists('css', '.non-member-hello-message');

    // Admin don't see the message.
    $this->drupalLogin($adminUser);
    $this->drupalGet($node->toUrl());
    $this->assertSession()->statusCodeEquals(Response::HTTP_OK);
    $this->assertSession()->elementNotExists('css', '.non-member-hello-message');
    $this->drupalLogout();

    // Authenticated non-admin user should see the message.
    $user = $this->createUser();
    $this->drupalLogin($user);
    $this->drupalGet($node->toUrl());
    $this->assertSession()->statusCodeEquals(Response::HTTP_OK);
    $this->assertSession()->elementExists('css', '.non-member-hello-message');
    $this->drupalLogout();
  }

}

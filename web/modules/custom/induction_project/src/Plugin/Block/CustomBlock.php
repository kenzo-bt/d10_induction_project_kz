<?php

namespace Drupal\induction_project\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a 'Custom' Block.
 */
#[Block(
  id: "custom_block",
  admin_label: new TranslatableMarkup("Custom block"),
  category: new TranslatableMarkup("Custom")
)]
class CustomBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t('Hello, World!'),
    ];
  }

}
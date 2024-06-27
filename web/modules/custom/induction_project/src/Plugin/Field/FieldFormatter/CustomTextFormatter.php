<?php

namespace Drupal\induction_project\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'custom_text_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "custom_text_formatter",
 *   label = @Translation("Custom text formatter"),
 *   field_types = {
 *     "string"
 *   }
 * )
 */
class CustomTextFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#theme' => 'custom_text_formatter',
        '#text' => $this->t($item->value),
      ];
    }
    return $elements;
  }

}
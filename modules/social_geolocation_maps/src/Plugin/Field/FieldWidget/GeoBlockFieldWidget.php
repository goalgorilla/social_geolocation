<?php

namespace Drupal\social_geolocation\Plugin\Field\FieldWidget;

use Drupal\block_field\Plugin\Field\FieldWidget\BlockFieldWidget as BlockFieldWidgetBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'block_field' widget.
 *
 * @FieldWidget(
 *   id = "block_field_default",
 *   label = @Translation("Block field"),
 *   field_types = {
 *     "block_field"
 *   }
 * )
 */
class GeoBlockFieldWidget extends BlockFieldWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    if ($element['#field_parents'][0] != 'field_landing_page_section') {
      return $element;
    }

    $options = [];

    $groups = [
      $this->t('Lists (Views)')->render() => $this->t('Basic'),
      $this->t('Social Landing Page')->render() => $this->t('Basic'),
    ];

    $blocks = [
      'social_geolocation_leaflet_commonmap_with_marker_icons-block_upcomming_events_map' => $this->t('Upcomming Events on a map'),
    ];

    foreach ($element['plugin_id']['#options'] as $title => $items) {
      if (isset($groups[$title])) {
        $title = $groups[$title];
      }
      else {
        $title = $this->t('Extra');
      }

      $title = $title->render();

      if (!isset($options[$title])) {
        $options[$title] = [];
      }

      foreach ($items as $key => $value) {
        if (isset($blocks[$key])) {
          $value = $blocks[$key];
        }

        $options[$title][$key] = $value;
      }
    }

    foreach ($options as &$option) {
      asort($option);
    }

    $element['plugin_id']['#options'] = $options;

    return $element;
  }

}

<?php

namespace Drupal\social_geolocation_maps\Plugin\views\cache;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\cache\Tag;

/**
 * Simple caching of query results for Views displays.
 *
 * @ingroup views_cache_plugins
 *
 * @ViewsCache(
 *   id = "social_geolocation_maps_tag",
 *   title = @Translation("Social Geolocation Maps tag based"),
 *   help = @Translation("Tag based caching of data. Caches will persist until
 *   any related cache tags are invalidated.")
 * )
 */
class SocialGeolocationMapsTag extends Tag {

  /**
   * {@inheritdoc}
   */
  protected $usesOptions = TRUE;

  /**
   * {@inheritdoc}
   */
  public function summaryTitle() {
    return $this->t('Social Geolocation Maps Tag');
  }

  /**
   * {@inheritdoc}
   */
  public function defineOptions() {
    $options = parent::defineOptions();
    $options['social_geolocation_maps_tag'] = ['default' => ''];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $form['social_geolocation_maps_tag'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Maps tag list'),
      '#description' => $this->t('Tags list, separated by new lines. Caching based on those cache tags must be manually cleared using custom code.'),
      '#default_value' => $this->options['social_geolocation_maps_tag'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $maps_tags = preg_split('/\r\n|[\r\n]/', $this->options['social_geolocation_maps_tag']);
    $maps_tags = array_map('trim', $maps_tags);
    return Cache::mergeTags($maps_tags, $this->view->storage->getCacheTags());
  }

}

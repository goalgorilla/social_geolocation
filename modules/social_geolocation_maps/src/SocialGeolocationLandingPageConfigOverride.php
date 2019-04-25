<?php

namespace Drupal\social_geolocation_maps;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\StorageInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Example configuration override.
 */
class SocialGeolocationLandingPageConfigOverride implements ConfigFactoryOverrideInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Constructs the configuration override.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The Drupal configuration factory.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ModuleHandlerInterface $module_handler) {
    $this->configFactory = $config_factory;
    $this->moduleHandler = $module_handler;
  }

  /**
   * Returns config overrides.
   *
   * @param array $names
   *   A list of configuration names that are being loaded.
   *
   * @return array
   *   An array keyed by configuration name of override data. Override data
   *   contains a nested array structure of overrides.
   * @codingStandardsIgnoreStart
   */
  public function loadOverrides($names) {
    $overrides = [];

    // Override social landing page references if the module is enabled,
    // so our custom blocks are also added.
    if (!$this->moduleHandler->moduleExists('social_landing_page')) {
      return $overrides;
    }

    $config_overrides = [
      'field.field.paragraph.block.field_block_reference_secondary',
      'field.field.paragraph.block.field_block_reference',
    ];

    // Add landing page block to primary and secondary block options.
    foreach ($config_overrides as $config_name) {
      if (in_array($config_name, $names)) {
        // Grab current configuration and push the new values.
        $config = $this->configFactory->getEditable($config_name);
        // We have to add config dependencies to field storage.
        $settings = $config->getOriginal('settings', FALSE)['plugin_ids'];
        $settings['views_block:social_geolocation_leaflet_commonmap_with_marker_icons-block_upcomming_events_map'] = 'views_block:social_geolocation_leaflet_commonmap_with_marker_icons-block_upcomming_events_map';

        $overrides[$config_name]['settings']['plugin_ids'] = $settings;
      }
    }

    return $overrides;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheSuffix() {
    return 'SocialGeolocationLandingPageConfigOverride';
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata($name) {
    return new CacheableMetadata();
  }

  /**
   * {@inheritdoc}
   */
  public function createConfigObject($name, $collection = StorageInterface::DEFAULT_COLLECTION) {
    return NULL;
  }

}

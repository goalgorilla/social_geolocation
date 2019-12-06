<?php

namespace Drupal\social_geolocation_search;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\StorageInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Config override sapi social_all social_content social_groups social_users.
 */
class SocialGeolocationSearchApiConfigOverride implements ConfigFactoryOverrideInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Whether we use SOLR.
   *
   * TRUE if we use SOLR, FALSE if the database is used.
   *
   * @var bool
   */
  protected $searchUsesSolr;

  /**
   * Constructs the configuration override.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The Drupal configuration factory.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The Drupal module handler.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ModuleHandlerInterface $module_handler) {
    $this->configFactory = $config_factory;
    // If the `social_search_solr` module is active all indexes use solr.
    $this->searchUsesSolr = $module_handler->moduleExists('social_search_solr');
  }

  /**
   * Returns config overrides for Search API indexes for the Geolocation field.
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

    if (in_array('search_api.index.social_all', $names, TRUE)) {
      $overrides['search_api.index.social_all'] = array_merge_recursive(
        $this->getIndexOverrides('group', 'group'),
        $this->getIndexOverrides('node', 'event'),
        $this->getIndexOverrides('profile', 'profile')
      );
    }

    if (in_array('search_api.index.social_groups', $names, TRUE)) {
      $overrides['search_api.index.social_groups'] = $this->getIndexOverrides('group', 'group');
    }

    if (in_array('search_api.index.social_content', $names, TRUE)) {
      $overrides['search_api.index.social_content'] = $this->getIndexOverrides('node', 'event');
    }

    if (in_array('search_api.index.social_users', $names, TRUE)) {
      $overrides['search_api.index.social_users'] = $this->getIndexOverrides('profile', 'profile');
    }

    return $overrides;
  }

  /**
   * Returns the geolocation field overrides for a certain entity.
   *
   * @param string $entity_type
   *   The entity type to load overrides for.
   * @param string $entity_bundle
   *   The bundle after which the fields are named.
   *
   * @return array
   *   The overrides that can be returned by a configuration override.
   */
  protected function getIndexOverrides(string $entity_type, string $entity_bundle) : array {
    return [
      'dependencies' => [
        'config' => [
          "field.storage.${entity_type}.field_${entity_bundle}_geolocation" => "field.storage.${entity_type}.field_${entity_bundle}_geolocation",
        ],
      ],
      'field_settings' => [
        "${entity_type}_geolocation" => [
          'label' => 'Geolocation',
          'datasource_id' => "entity:${entity_type}",
          'property_path' => "field_${entity_bundle}_geolocation",
          'type' => $this->searchUsesSolr ? 'location' : 'string',
          'dependencies' => [
            'config' => [
              "field.storage.node.field_${entity_bundle}_geolocation",
            ],
          ],
        ],
        "${entity_type}_lat_cos" => [
          'label' => 'Geolocation » Latitude cosine',
          'datasource_id' => "entity:${entity_type}",
          'property_path' => "field_${entity_bundle}_geolocation:lat_cos",
          'type' => 'decimal',
          'dependencies' => [
            'config' => [
              "field.storage.node.field_${entity_bundle}_geolocation",
            ],
          ],
        ],
        "${entity_type}_lat_sin" => [
          'label' => 'Geolocation » Latitude sine',
          'datasource_id' => "entity:${entity_type}",
          'property_path' => "field_${entity_bundle}_geolocation:lat_sin",
          'type' => 'decimal',
          'dependencies' => [
            'config' => [
              "field.storage.node.field_${entity_bundle}_geolocation",
            ],
          ],
        ],
        "${entity_type}_lng_rad" => [
          'label' => 'Geolocation » Longitude radian',
          'datasource_id' => "entity:${entity_type}",
          'property_path' => "field_${entity_bundle}_geolocation:lng_rad",
          'type' => 'decimal',
          'dependencies' => [
            'config' => [
              "field.storage.node.field_${entity_bundle}_geolocation",
            ],
          ],
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheSuffix() {
    return 'SocialGeolocationConfigOverride';
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

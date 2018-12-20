<?php

namespace Drupal\social_geolocation;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\StorageInterface;

/**
 * Example configuration override.
 */
class SocialGeolocationUserAdminPeopleConfigOverride implements ConfigFactoryOverrideInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs the configuration override.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The Drupal configuration factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
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

    $config_name = 'views.view.user_admin_people';

    if (in_array($config_name, $names)) {

      // Grab current configuration and push the new values.
      $config = $this->configFactory->getEditable($config_name);
      // We have to add config dependencies to field storage.
      $dependencies = $config->getOriginal('dependencies', FALSE)['module'];
      $dependencies[] = 'social_geolocation';

      $overrides[$config_name]['dependencies']['module'] = $dependencies;

      /** @TODO Get dependencies array + add to it */
      $overrides[$config_name]['display']['default'] = [
        'display_options' => [
          'group_by' => TRUE,
          'filters' => [
            'field_profile_geolocation_proximity' => [
              'value' => [
                'min' => '',
                'max' => '',
                'value' => '',
              ],
              'admin_label' => '',
              'operator' => '<=',
              'proximity_source' => 'exposed',
              'proximity_lat' => '',
              'proximity_lng' => '',
              'proximity_units' => 'km',
              'proximity_argument' => '',
              'entity_id_argument' => '',
              'boundary_filter' => '',
              'client_location' => false,
              'id' => 'field_profile_geolocation_proximity',
              'table' => 'profile__field_profile_geolocation',
              'field' => 'field_profile_geolocation_proximity',
              'relationship' => 'profile',
              'group_type' => 'group',
              'group' => '1',
              'exposed' => true,
              'expose' => [
                'operator_id' => 'field_profile_geolocation_proximity_op',
                'label' => 'Distance in kilometers',
                'description' => '',
                'use_operator' => false,
                'operator' => 'field_profile_geolocation_proximity_op',
                'identifier' => 'field_profile_geolocation_proximity',
                'required' => false,
                'remember' => false,
                'multiple' => false,
                'remember_roles' => [
                  'authenticated' => 'authenticated',
                  'anonymous' => '0',
                  'administrator' => '0',
                  'contentmanager' => '0',
                  'sitemanager' => '0',
                ],
                'placeholder' => '',
                'min_placeholder' => '',
                'max_placeholder' => '',
                'input_by_geocoding_widget' => 0,
                'geocoder_plugin_settings' => [
                  'plugin_id' => 'google_geocoding_api',
                  'settings' => [
                    'components' => [
                      'route' => '',
                      'locality' => '',
                      'administrativeArea' => '',
                      'postalCode' => '',
                      'country' => '',
                    ],
                  ],
                ],
              ],
              'is_grouped' => false,
              'group_info' => [
                'label' => '',
                'description' => '',
                'identifier' => '',
                'optional' => true,
                'widget' => 'select',
                'multiple' => false,
                'remember' => false,
                'default_group' => 'All',
                'default_group_multiple' => [],
                'group_items' => [],
              ],
              'plugin_id' => 'geolocation_filter_proximity',
            ],
          ],
        ],
      ];
    }

    return $overrides;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheSuffix() {
    return 'SocialGeolocationUserAdminPeopleConfigOverride';
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

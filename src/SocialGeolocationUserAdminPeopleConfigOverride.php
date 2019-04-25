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

    if (in_array($config_name, $names, TRUE)) {
      $overrides[$config_name] = [
        'dependencies' => [
          'module' => [
            // Use a string as key here so we don't overwrite anything.
            'social_geolocation' => 'social_geolocation',
            'geolocation' => 'geolocation',
          ],
        ],
        'display' => [
          'default' => [
            'display_options' => [
              'filters' => [
                'field_profile_geolocation_proximity' => [
                  'id' => 'field_profile_geolocation_proximity',
                  'table' => 'profile__field_profile_geolocation',
                  'field' => 'field_profile_geolocation_proximity',
                  'relationship' => 'profile',
                  'group_type' => 'group',
                  'admin_label' => '',
                  'operator' => '<=',
                  'value' => [
                    'min' => '',
                    'max' => '',
                    'value' => '',
                    'center' => '',
                  ],
                  'group' => 1,
                  'exposed' => TRUE,
                  'expose' => [
                    'operator_id' => 'field_profile_geolocation_proximity_op',
                    'label' => 'Proximity (field_profile_geolocation)',
                    'description' => '',
                    'use_operator' => FALSE,
                    'operator' => 'field_profile_geolocation_proximity_op',
                    'identifier' => 'field_profile_geolocation_proximity',
                    'required' => FALSE,
                    'remember' => FALSE,
                    'multiple' => FALSE,
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
                  ],
                  'is_grouped' => FALSE,
                  'group_info' => [
                    'label' => '',
                    'description' => '',
                    'identifier' => '',
                    'optional' => TRUE,
                    'widget' => 'select',
                    'multiple' => FALSE,
                    'remember' => FALSE,
                    'default_group' => 'All',
                    'default_group_multiple' => [],
                    'group_items' => [],
                  ],
                  'location_input' => [
                    'client_location' => [
                      'weight' => 0,
                      'enable' => FALSE,
                      'location_input_id' => 'client_location',
                      'settings' => [
                        'auto_submit' => FALSE,
                        'hide_form' => FALSE,
                      ],
                    ],
                    'coordinates' => [
                      'enable' => TRUE,
                      'weight' => 0,
                      'location_input_id' => 'coordinates',
                    ],
                    'geocoder' => [
                      'settings' => [
                        'plugin_id' => 'nominatim',
                        'settings' => [
                          'label' => 'Address',
                          'description' => '',
                        ],
                        'auto_submit' => FALSE,
                        'hide_form' => FALSE,
                      ],
                      'weight' => 0,
                      'enable' => FALSE,
                      'location_input_id' => 'geocoder',
                    ],
                    'fixed_value:fixed_value' => [
                      'settings' => [
                        'location_settings' => [
                          'settings' => [
                            'latitude' => '',
                            'longitude' => '',
                          ],
                        ],
                        'location_plugin_id' => 'fixed_value',
                      ],
                      'weight' => 0,
                      'enable' => FALSE,
                      'location_input_id' => 'location_plugins',
                    ],
                    'freeogeoip:freeogeoip' => [
                      'weight' => 0,
                      'enable' => FALSE,
                      'location_input_id' => 'location_plugins',
                      'settings' => [
                        'location_plugin_id' => 'freeogeoip',
                      ],
                    ],
                  ],
                  'unit' => 'km',
                  'plugin_id' => 'geolocation_filter_proximity',
                ],
              ],
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

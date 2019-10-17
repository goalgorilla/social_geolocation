<?php

/**
 * @file
 * Post update hooks for the Social Geolocation module.
 */

use Drupal\field\Entity\FieldConfig;

/**
 * Enable Social Geolocation Search module.
 */
function social_geolocation_post_update_8002_enable_search_submodule() {
  // The social_geolocation_search module has been added because search was
  // always optional based on the search_api module, however, the new
  // implementation will actually require things so it's better if we can switch
  // the search support off entirely. It's enabled by default if the search_api
  // module is enabled to keep current behaviour.
  if (\Drupal::moduleHandler()->moduleExists('search_api')) {
    \Drupal::service('module_installer')->install(['social_geolocation_search']);
  }
}

/**
 * Remove configuration for the geocoder module as it's no longer used.
 */
function social_geolocation_post_update_8003_remove_geocoder_configuration() {
  $bundles = [
    ['group', 'open_group'],
    ['group', 'closed_group'],
    ['group', 'public_group'],
    ['node', 'event'],
    ['profile', 'profile'],
  ];

  $field_ids = array_map(
    static function ($e) {
      return "${e[0]}.${e[1]}.field_${e[0]}_geolocation";
    },
    $bundles
  );

  $fields = FieldConfig::loadMultiple($field_ids);

  foreach ($fields as $field) {
    $field
      ->unsetThirdPartySetting('geolocation_address', 'enable')
      ->unsetThirdPartySetting('geolocation_address', 'address_field')
      ->unsetThirdPartySetting('geolocation_address', 'geocoder')
      ->unsetThirdPartySetting('geolocation_address', 'sync_mode')
      ->unsetThirdPartySetting('geolocation_address', 'direction')
      ->unsetThirdPartySetting('geolocation_address', 'button_position')
      ->save();
  }
}

<?php

/**
 * @file
 * Post update hooks for the Social Geolocation module.
 */

use Drupal\field\Entity\FieldConfig;

/**
 * Configure geolocation fields to pull data from Address fields.
 */
function social_geolocation_post_update_8001_update_field_configuration() {
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
    $entity_id = $field->getTargetEntityTypeId();
    $bundle_id = $field->getTargetBundle();
    $field
      ->setThirdPartySetting('geolocation_address', 'enable', TRUE)
      ->setThirdPartySetting('geolocation_address', 'address_field', "${entity_id}.${bundle_id}.field_${entity_id}_address")
      ->setThirdPartySetting('geolocation_address', 'geocoder', NULL)
      ->setThirdPartySetting('geolocation_address', 'sync_mode', 'auto')
      ->setThirdPartySetting('geolocation_address', 'direction', 'duplex')
      ->setThirdPartySetting('geolocation_address', 'button_position', NULL)
      ->save();
  }

}

<?php

namespace Drupal\social_geolocation\Plugin\Geocoder\Preprocessor;

use Drupal\geocoder_field\PreprocessorBase;

/**
 * Provides a geocoder preprocessor plugin for address fields.
 *
 * @GeocoderPreprocessor(
 *   id = "event_address",
 *   name = "Event Address",
 *   field_types = {
 *     "address"
 *   }
 * )
 */
class Address extends PreprocessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocess() {
    parent::preprocess();

    $defaults = [
      'address_line1' => NULL,
      'locality' => NULL,
      'dependent_locality' => NULL,
      'administrative_area' => NULL,
      'postal_code' => NULL,
      'country_code' => NULL,
    ];

    foreach ($this->field->getValue() as $delta => $value) {
      $value += $defaults;
      $address = [
        $value['address_line1'],
        $value['locality'],
        $value['dependent_locality'],
        str_replace($value['country_code'] . '-', '', $value['administrative_area']),
      ];

      // For canada we need to remove postal code from the Address lookup.
      // See https://github.com/openstreetmap/Nominatim/issues/1052.
      // Canada has issues with postal codes and returning correct lat lng data.
      if ($value['country_code'] !== NULL && $value['country_code'] !== 'CA') {
        $address[] = $value['postal_code'];
      }

      $address[] = $value['country_code'];

      // The value will be used for geocoding, lets make sure Google Api / OSM
      // has the best results possible by formatting it correctly.
      $value['value'] = _social_geolocation_address_to_string($value);

      // Fallback for when our geocoding resulted in to nothing.
      if ($value['value']) {
        $value['value'] = implode(',', array_filter($address));
      }

      $this->field->set($delta, $value);
    }

    return $this;
  }

}

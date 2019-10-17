<?php

namespace Drupal\social_geolocation\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

// Defines the plugin name for the OpenStreetMap geocoder plugin.
define('OPENSTREETMAP_PLUGIN_ID', 'nominatim');

// Defines the plugin name for the Google Geocoder API geocoder plugin.
define('GOOGLE_GEOCODER_API_PLUGIN_ID', 'google_geocoding_api');

/**
 * Class SocialGeolocationSettings.
 */
class SocialGeolocationSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'social_geolocation.settings',
      // This config is edited to update the API key if the Google API is used.
      'geolocation_google_maps.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'social_geolocation_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('social_geolocation.settings');

    $form['geolocation_provider'] = [
      '#type' => 'radios',
      '#title' => $this->t('Provider to use for storing Geolocation data'),
      '#description' => $this->t('Select which provider Open Social should use to convert address data in to geolocation information.'),
      '#default_value' => $config->get('geolocation_provider'),
      // The key of the options here should be the id of a Geocoder plugin
      // provided by the geolocation module or one of it's sub-modules.
      // The label should be a string that is understandable to humans.
      // Values will be added based on the modules that are enabled.
      '#options' => [],
    ];

    $form['unit_of_measurement'] = [
      '#type' => 'radios',
      '#title' => $this->t('Unit of measurement'),
      '#description' => $this->t('Select the unit of measurement that is used on this platform for proximity search/filtering.'),
      '#default_value' => $config->get('unit_of_measurement'),
      '#options' => [
        'km' => 'Kilometers',
        'mi' => 'Miles',
      ],
    ];

    // Add the nominatim provider from OpenStreetMap if the geolocation_leaflet
    // module that contains the geocoder is enabled.
    if (\Drupal::moduleHandler()->moduleExists('geolocation_leaflet')) {
      // The label is intentionally not translatable because it's a brand name.
      $form['geolocation_provider']['#options'][OPENSTREETMAP_PLUGIN_ID] = 'OpenStreetMap';
    }

    // Add the Google Geocoder API if the geolocation_google_maps module that
    // contains the geocoder is enabled.
    if (\Drupal::moduleHandler()->moduleExists('geolocation_google_maps')) {
      // The label is intentionally not translatable because it's a brand name.
      $form['geolocation_provider']['#options'][GOOGLE_GEOCODER_API_PLUGIN_ID] = 'Google Geocoding API';

      $geoconfig = $this->config('geolocation_google_maps.settings');

      // @todo Ideally this would be required if it's visible but the required
      //   property can't simply be set because it would stop non-google
      //   providers from being selected.
      $form['geolocation_google_map_api_key'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Google Maps API key'),
        '#description' => $this->t('Google requires users to use a valid API key. Using the <a href="https://console.developers.google.com/apis">Google API Manager</a>, you can enable the <em>Google Maps JavaScript API</em>. That will create (or reuse) a <em>Browser key</em> which you can paste here.'),
        '#default_value' => $geoconfig->get('google_map_api_key'),
        '#states' => [
          'visible' => [
            ':input[name="geolocation_provider"]' => ['value' => GOOGLE_GEOCODER_API_PLUGIN_ID],
          ],
        ],
      ];

      // If the value is overwritten through a configuration override then the
      // field is disabled and feedback is provided to the user.
      // This is called on the immutable configuration object because overrides
      // are not applied in ConfigFactory::doGet for mutable config objects.
      if ($this->configFactory->get('geolocation_google_maps.settings')->hasOverrides('google_map_api_key')) {
        $form['geolocation_google_map_api_key']['#disabled'] = TRUE;
        $form['geolocation_google_map_api_key']['#description'] .= $this->t('<b>This value is controlled by a configuration overwrite and can not be edited. Contact the site administrator to change this.</b>');
      }
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('social_geolocation.settings')
      ->set('geolocation_provider', $form_state->getValue('geolocation_provider'))
      ->set('unit_of_measurement', $form_state->getValue('unit_of_measurement'))
      ->save();

    // If the Google Geocoder API is used for geocoding then the API key used
    // by the plugin should be updated. For other providers we just leave the
    // configuration as is.
    if ($form_state->getValue('geolocation_provider') === GOOGLE_GEOCODER_API_PLUGIN_ID) {
      $this->config('geolocation_google_maps.settings')
        ->set('google_map_api_key', $form_state->getValue('geolocation_google_map_api_key'))
        ->save();
    }
  }

}

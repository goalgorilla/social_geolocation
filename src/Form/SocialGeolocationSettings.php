<?php

namespace Drupal\social_geolocation\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

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
      '#options' => [
        'google' => 'Google Maps API',
        'openstreetmaps' => 'OpenStreetMap',
      ],
    ];

    $geoconfig = $this->configFactory()->getEditable('geolocation.settings');
    $form['geolocation_google_map_api_key'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Google Maps API key'),
      '#description' => $this->t('Google requires users to use a valid API key. Using the <a href="https://console.developers.google.com/apis">Google API Manager</a>, you can enable the <em>Google Maps JavaScript API</em>. That will create (or reuse) a <em>Browser key</em> which you can paste here.'),
      '#default_value' => $geoconfig->get('geolocation_google_map_api_key'),
      '#states' => array(
        'visible' => array(
          ':input[name="geolocation_provider"]' => array('value' => 'google'),
        ),
      ),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('social_geolocation.settings')
      ->set('geolocation_provider', $form_state->getValue('geolocation_provider'))
      ->save();

    // If users chooses Geolocation to be Google API we need to ensure
    // API key is filled in and we can store it in the Geolocation module
    // settings.
    if ($form_state->getValue('geolocation_provider') === 'google') {
      $config = $this->configFactory()->getEditable('geolocation.settings');
      $config->set('google_map_api_key', $form_state->getValue('geolocation_google_map_api_key'));
    }
  }

}

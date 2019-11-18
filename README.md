# Social Geolocation

## INTRODUCTION
This module provides the ability to convert address field values into a set of
coordinates that are stored with the entity. This enables the content to be 
found in search based on location.

## REQUIREMENTS
- `commerceguys/addressing:^1.0.0`
- `drupal/address:^1.0`
- `drupal/core:^8.x`
- `drupal/geocoder:~2.0`
- `drupal/geolocation": 2-dev`
- `drupal/search_api_location:^1.0`
- `drupal/social:>6.0`

## INSTALLATION
Please use composer to install this module with its requirements.

Once installed you can use `drush` or the Drupal extensions page to enable this 
module. You must enable at least one supported geolocation geocoding plugin.

## CONFIGURATION
This module allows you to select which geocoding plugin should be used and 
specify a Google Maps API key if that geocoder is selected. See 
"Supported Geocoding plugins" below for an overview of which plugins are 
supported.

### Supported Geocoding plugins
#### OpenStreetMap
By default this module uses the OpenStreetMap API. It is not needed to enter an 
API key. 

You can find the usage policy here:
https://wiki.openstreetmap.org/wiki/API_usage_policy

#### Google Maps API Key
Optionally you can use the Google Maps API to transform address strings into 
lattitude/longtitude pairs. For all server side requests no Google Maps API
key is needed, however, rate limiting may apply. For the client side requests
which include the proximity filter and map blocks, a valid maps API key must
be entered on the geolocation's configuration page.

You can generate a key here:
https://console.cloud.google.com/google/maps-apis/api-list?project=social-local-171213&organizationId=841499249988

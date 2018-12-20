This module provides the ability to convert address field values into a set of
coordinates that are stored with the entity. This enables the content to be 
found in search based on location.

## OpenStreetMap
By default this module uses the OpenStreetMap API. It is not needed to enter an API key. 

You can find the usage policy here:
https://wiki.openstreetmap.org/wiki/API_usage_policy

## Google Maps API Key
Optionally you can use the Google Maps API to transform address strings into 
lattitude/longtitude pairs. For all server side requests no Google Maps API
key is needed, however, rate limiting may apply. For the client side requests
which include the proximity filter and map blocks, a valid maps API key must
be entered on the geolocation's configuration page.

You can generate a key here:
https://console.cloud.google.com/google/maps-apis/api-list?project=social-local-171213&organizationId=841499249988

## Configuring your own Address fields to store geolocation data
You can simply enable the `field_ui` module

See step "Geocoding results from Address field": 
https://www.drupal.org/docs/8/modules/geolocation-field/configuring-integration-between-geolocation-and-address-field

And it should store your Address data in geolocation fields as well. 

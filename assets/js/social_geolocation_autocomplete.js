(function ($) {
  'use strict';

  Drupal.behaviors.socialGeolocationAutocomplete = {
    attach: function (context, settings) {
      $("input[name='geolocation_geocoder_google_geocoding_api']").change(function () {
        if (!$('.geolocation-geocoder-google-geocoding-api-state').val()) {
          if ($('#ui-id-1 .ui-menu-item-wrapper').first().html() && $('#ui-id-1 .ui-menu-item-wrapper').first().html().length > 0) {
            $('#ui-id-1 .ui-menu-item-wrapper').first().click();
          }
        }
      });
    }
  };
})(jQuery);

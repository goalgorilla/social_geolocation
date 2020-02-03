(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.mapsSocialLazyLoadReactivate = {
    attach: function (context, setting) {
      $(document).on('click', '.leaflet-marker-icon', function () {
        var bLazy = new Blazy();
        bLazy.revalidate();
      });
    }
  };

})(jQuery, Drupal);

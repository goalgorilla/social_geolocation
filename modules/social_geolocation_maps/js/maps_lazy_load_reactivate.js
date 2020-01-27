(function ($) {

  'use strict';

  Drupal.behaviors.mapsSocialLazyLoadReactivate = {
    attach: function (context, setting) {

      $('.leaflet-marker-icon').on('click', function () {
        var bLazy = new Blazy();
        bLazy.revalidate();
      });
    }
  };

})(jQuery);

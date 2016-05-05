(function($) {
  // Site title
  wp.customize('blogname', function(value) {
    value.bind(function(to) {
      $('.navbar-brand').text(to);
    });
  });
})(jQuery);

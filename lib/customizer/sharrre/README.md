jQuery Sharrre Plugin
===

Make your sharing widget!
Sharrre is a jQuery plugin that allows you to create nice widgets sharing for Facebook, Twitter, Google Plus (with PHP script) and more.
More information on [Sharrre] (http://sharrre.com/#demos)

Usage
===

	$('#sharrre').sharrre({
    share: {
      googlePlus: true,
      facebook: true,
      twitter: true
    },
    url: 'http://sharrre.com'
  });

Example
===
    
  <div id="demo1" data-title="sharrre" data-url="http://sharrre.com" ></div>
  $(document).ready(function(){
    $('#demo1').sharrre({
      share: {
        googlePlus: true,
        facebook: true,
        twitter: true,
        delicious: true
      },
      buttons: {
        googlePlus: {size: 'tall'},
        facebook: {layout: 'box_count'},
        twitter: {count: 'vertical'},
        delicious: {size: 'tall'}
      },
      hover: function(api, options){
        $(api.element).find('.buttons').show();      
      },
      hide: function(api, options){
        $(api.element).find('.buttons').hide();
      }
    });
  });

  See example on [official website] (http://sharrre.com/#demos)
	

Dependencies
===

jQuery 1.7

Author
===

- [Julien Hany](http://hany.fr)
- [Twitter (@_JulienH)](http://twitter.com/_JulienH)
- [Google+](http://plus.google.com/111637545317893682325)

jQuery(window).load(function() {
	// Add social buttons
	addSocialButtons('#content');
});

function addSocialButtons($context){
	if( typeof $context === 'undefined' ) $context = jQuery('#content'); 
	// Social share
	if( jQuery('.social-share', $context).length > 0 ){
		jQuery('.twitter-share', $context).sharrre({
			share : {
				twitter : true
			},
			template : shoestrapScript.sharehtml,
			enableHover : false,
			click : function(api, options) {
				api.simulateClick();
				api.openPopup('twitter');
			}
		});
		jQuery('.facebook-share', $context).sharrre({
			share : {
				facebook : true
			},
			template : shoestrapScript.sharehtml,
			enableHover : false,
			click : function(api, options) {
				api.simulateClick();
				api.openPopup('facebook');
			}
		});
		jQuery('.pinterest-share', $context).each(function(index, domElem){
			jQuery(this).sharrre({
				share : {
					pinterest : true
				},
				buttons: { 
				   pinterest: {
						url: jQuery(this).attr('data-url'),
						media: jQuery(this).attr('data-media'),
						description: jQuery(this).attr('data-description')
					}
				},
				template : shoestrapScript.sharehtml,
				enableHover : false,
				urlCurl: shoestrapScript.sharrrephp,
				click : function(api, options) {
					api.simulateClick();
					api.openPopup('pinterest');
				}
			});
		});
		jQuery('.googleplus-share', $context).sharrre({
			share: {
				googlePlus: true
			},
			template: shoestrapScript.sharehtml,
			enableHover: false,
			urlCurl: shoestrapScript.sharrrephp,
			click: function(api, options){
				api.simulateClick();
				api.openPopup('googlePlus');
			}
		});
	}
}

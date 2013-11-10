/*global jQuery, document, redux_change */
(function($){
	'use strict';

	$.redux = $.redux || {};

	$(document).ready(function(){
		$.redux.edd();
	});

	$.redux.edd = function(){
		jQuery('.redux-EDDAction').click(function() {

			var parent = jQuery(this).parents('.redux-container-edd:first');
			var id = parent.attr('id');
			var theData = {};
			parent.find('.redux-edd').each(function() {
				theData[jQuery(this).attr('id').replace(jQuery(this).attr('data-id')+'-', '')] = jQuery(this).val();
			});
			theData['edd_action'] = jQuery(this).attr('data-edd_action');
			theData['opt_name'] = redux_opts.opt_name;
			jQuery.post(
			    ajaxurl, {
			        'action': 'redux_edd_'+redux_opts.opt_name+'_license',
			        'data': theData
			    },
			    function(response) {
			    	jQuery('#'+id+'-status').val(response.status);
			    	console.log(response.status);
			    	if (response.status === "active") {

			    	} else if (response.status === "deactivated") {
			    	} else { // Inactive or bad

			    	}
			        
			    }
			);			
		});
				
	}

})(jQuery);

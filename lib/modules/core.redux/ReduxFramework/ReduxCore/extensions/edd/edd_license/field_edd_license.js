/*global jQuery, document */
(function($){
	'use strict';

	$.redux = $.redux || {};

	$(document).ready(function(){
		$.redux.edd();
	});

	$.redux.edd = function(){
		jQuery( document ).on( "click", ".redux-EDDAction", function(e) {
			e.preventDefault();
			var parent = jQuery(this).parents('.redux-container-edd_field:first');
			var id = jQuery(this).attr('data-id');
			
			var theData = {};
			parent.find('.redux-edd').each(function() {
				theData[jQuery(this).attr('id').replace(id+'-', '')] = jQuery(this).val();
			});
			theData['edd_action'] = jQuery(this).attr('data-edd_action');
			theData['opt_name'] = redux_opts.opt_name;
			
			jQuery.post(
			    ajaxurl, {
			        'action': 'redux_edd_'+redux_opts.opt_name+'_license',
			        'data': theData
			    },
			    function(response) {
			    	response = jQuery.parseJSON(response);
			    	jQuery('#'+id+'-status').val(response.status);
			    	jQuery('#'+id+'-status_notice').html(response.status);
			    	if (response.status === "active") {
			    		jQuery('#'+id+'-notice').attr('class', 'redux-info');
			    	} else if (response.status === "deactivated") {
			    	} else { // Inactive or bad

			    	}
			        
			    }
			);			
		});
				
	}

})(jQuery);

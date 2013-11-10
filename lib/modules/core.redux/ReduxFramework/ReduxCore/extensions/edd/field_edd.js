/*global jQuery, document, redux_change */
(function($){
	'use strict';

	$.redux = $.redux || {};

	$(document).ready(function(){
		$.redux.edd();
	});

	$.redux.edd = function(){
		jQuery('.redux-verifyEDD').click(function() {

			var parent = jQuery(this).parents('.redux-container-edd:first');
			var id = parent.attr('id');
			var theData = {};
			parent.find('.redux-edd').each(function() {
				theData[jQuery(this).attr('id').replace('edd-', '')] = jQuery(this).val();
			});
			
			jQuery.post(
			    ajaxurl, {
			        'action': 'redux_edd_'+redux_opts.opt_name+'_verify_license',
			        'data': theData
			    },
			    function(response){
			        console.log(response);
			    }
			);			
		});
				
	}

})(jQuery);

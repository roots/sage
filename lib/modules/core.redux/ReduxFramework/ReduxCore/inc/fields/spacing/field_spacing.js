(function($){
	'use strict';

	$.redux = $.redux || {};

	$(document).ready(function(){
		$.redux.spacing();
	});

	$.redux.spacing = function(){
		$(".redux-spacing-top, .redux-spacing-bottom, .redux-spacing-left, .redux-spacing-right").numeric();

		$(".redux-spacing-units").select2({
			width: 'resolve',
			triggerChange: true,
			allowClear: true
		});

		$('.redux-spacing-input').on('change', function() {
			var units = jQuery(this).parents('.redux-field:first').find('.redux-spacing-units option:selected').val();
			if(typeof units !== 'undefined') {
				console.log(units);
				jQuery('#'+jQuery(this).attr('rel')).val(jQuery(this).val()+units);
			} else {
				jQuery('#'+jQuery(this).attr('rel')).val(jQuery(this).val());
			}
		});

		$('.redux-spacing-units').on('change', function() {
			$(this).parents('.redux-spacing-container:first').find('.redux-spacing-input').change();
			$('.redux-spacing-input').each(function() {
				$(this).change(); // Update the unit value
			});
		});
	}

})(jQuery);
jQuery(document).ready(function() {
	
	jQuery(".redux-dimensions-height, .redux-dimensions-width").numeric({
		//allowMinus   : false,
	});

	jQuery(".redux-dimensions-units").select2({
		width: 'resolve',
		triggerChange: true,
		allowClear: true
	});

	jQuery('.redux-dimensions-input').on('change', function() {
		var units = jQuery(this).parents('.redux-field:first').find('.field-units').val();
		if ( jQuery(this).parents('.redux-field:first').find('.redux-dimensions-units').length !== 0 ) {
			units = jQuery(this).parents('.redux-field:first').find('.redux-dimensions-units option:selected').val();
		}
		if( typeof units !== 'undefined' ) {
			jQuery('#'+jQuery(this).attr('rel')).val(jQuery(this).val()+units);
		} else {
			jQuery('#'+jQuery(this).attr('rel')).val(jQuery(this).val());
		}
	});

	jQuery('.redux-dimensions-units').on('change', function() {
		jQuery(this).parents('.redux-field:first').find('.redux-dimensions-input').change();
	});

});
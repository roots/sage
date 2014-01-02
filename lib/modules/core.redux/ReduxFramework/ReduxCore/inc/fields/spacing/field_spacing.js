jQuery(document).ready(function() {
	
	jQuery(".redux-spacing-top, .redux-spacing-right, .redux-spacing-bottom, .redux-spacing-left, .redux-spacing-all").numeric({
		//allowMinus   : false,
	});

	jQuery(".redux-spacing-units").select2({
		width: 'resolve',
		triggerChange: true,
		allowClear: true
	});

	jQuery('.redux-spacing-input').on('change', function() {
		var units = jQuery(this).parents('.redux-field:first').find('.field-units').val();
		if ( jQuery(this).parents('.redux-field:first').find('.redux-spacing-units').length !== 0 ) {
			units = jQuery(this).parents('.redux-field:first').find('.redux-spacing-units option:selected').val();
		}
		var value = jQuery(this).val();
		if( typeof units !== 'undefined' && value ) {
			value += units;
		}
		if ( jQuery(this).hasClass( 'redux-spacing-all' ) ) {
			jQuery(this).parents('.redux-field:first').find('.redux-spacing-value').each(function() {
				jQuery(this).val(value);
			});
		} else {
			jQuery('#'+jQuery(this).attr('rel')).val(value);
		}
	});
	jQuery('.redux-spacing-units').on('change', function() {
		jQuery(this).parents('.redux-field:first').find('.redux-spacing-input').change();
	});

});
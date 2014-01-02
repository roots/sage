/*
	Field Border (border)
 */


jQuery(document).ready(function() {
	
	jQuery(".redux-border-top, .redux-border-right, .redux-border-bottom, .redux-border-left, .redux-border-all").numeric({
		allowMinus   : false,
	});

	jQuery(".redux-border-style").select2({
		triggerChange: true,
		allowClear: true
	});

	jQuery('.redux-border-input').on('change', function() {
		var units = jQuery(this).parents('.redux-field:first').find('.field-units').val();
		if ( jQuery(this).parents('.redux-field:first').find('.redux-border-units').length !== 0 ) {
			units = jQuery(this).parents('.redux-field:first').find('.redux-border-units option:selected').val();
		}
		var value = jQuery(this).val();
		if( typeof units !== 'undefined' && value ) {
			value += units;
		}
		if ( jQuery(this).hasClass( 'redux-border-all' ) ) {
			jQuery(this).parents('.redux-field:first').find('.redux-border-value').each(function() {
				jQuery(this).val(value);
			});
		} else {
			jQuery('#'+jQuery(this).attr('rel')).val(value);
		}
	});
	jQuery('.redux-border-units').on('change', function() {
		jQuery(this).parents('.redux-field:first').find('.redux-border-input').change();
	});

});
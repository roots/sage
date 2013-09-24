jQuery(document).ready(function() {
	
	jQuery(".redux-spacing-top, .redux-spacing-bottom, .redux-spacing-left, .redux-spacing-right").numeric();

	jQuery(".redux-spacing-units").select2({
		width: 'resolve',
		triggerChange: true,
		allowClear: true
	});

	jQuery('.redux-spacing-input').on('change', function() {
		var units = jQuery(this).parents('.redux-spacing-container:first').find('.redux-spacing-units  option:selected').val();
		var id = jQuery(this).attr('rel');
		jQuery('#'+id).val(jQuery(this).val()+units);
	});

	jQuery('.redux-spacing-units').on('change', function() {
		jQuery(this).parents('.redux-spacing-container:first').find('.redux-spacing-input').change();
	});

});
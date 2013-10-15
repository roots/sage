jQuery(document).ready(function() {
	
	jQuery(".redux-dimensions-height, .redux-dimensions-width").numeric();

	jQuery(".redux-dimensions-units").select2({
		width: 'resolve',
		triggerChange: true,
		allowClear: true
	});

	jQuery('.redux-dimensions-input').on('change', function() {
		var units = jQuery(this).parents('.redux-field:first').find('.redux-dimensions-units option:selected').val();
		var id = jQuery(this).attr('rel');
		jQuery('#'+id).val(jQuery(this).val()+units);
	});

	jQuery('.redux-dimensions-units').on('change', function() {
		jQuery(this).parents('.redux-field:first').find('.redux-dimensions-input').change();
	});

});
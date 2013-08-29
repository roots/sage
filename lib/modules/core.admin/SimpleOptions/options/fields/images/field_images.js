/*
 *
 * SimpleOptions_images function
 * Changes the radio select option, and changes class on images
 *
 */

jQuery.noConflict();

jQuery(document).ready(function(){

	jQuery('.sof-images label').click(function() {

		var id = jQuery(this).attr('for');
		
		jQuery(this).parent().parent().find('.sof-images-selected').removeClass('sof-images-selected');	

		jQuery(this).find('input[type="radio"]').prop('checked');
		jQuery('label[for="'+id+'"]').addClass('sof-images-selected');
		var split = id.split('-');
		var labelclass = split[0];
		
	});

	jQuery('.sof-save-preset').live("click",function(e) {
		e.preventDefault();
		var presets = jQuery(this).parent().parent().find('.sof-presets label.sof-images-selected input[type="radio"]');
		var data = presets.data('presets');
		if (typeof(presets) !== undefined && presets !== null) {
			var answer = confirm(sof_opts.preset_confirm)
			if (answer){
				jQuery('#import-code-value').val(JSON.stringify(data));
				jQuery('#simple-options-import').click();
			}
		}
		return false;
	});

});
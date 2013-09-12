/* global confirm, redux_opts */

jQuery(document).ready(function() {

	// On label click, change the input and class
	jQuery('.redux-image-select label').click(function() {
		var id = jQuery(this).attr('for');
		jQuery(this).parents("td:first").find('.redux-image-select-selected').removeClass('redux-image-select-selected');
		jQuery(this).find('input[type="radio"]').prop('checked');
		jQuery('label[for="' + id + '"]').addClass('redux-image-select-selected');
	});

	// User clicks to save the preset. Make it happen!
	jQuery('.redux-save-preset').on("click", function(e) {
		e.preventDefault();
		var presets = jQuery(this).parents("td:first").find('input[type="radio"]:checked');
		var data = presets.data('presets');
		if (presets !== undefined && presets !== null) {
			var answer = confirm(redux_opts.preset_confirm);
			if (answer) {
				window.onbeforeunload = null;
				jQuery('#import-code-value').val(JSON.stringify(data));
				jQuery('#redux-import').click();
			}
		}
		return false;
	});

	// Used to display a full image preview of a tile/pattern
	var xOffset = 10; // these 2 variable determine the popup's distance from the cursor
	var yOffset = 30;
	
	jQuery(".tiles").hover(function(e) {
		jQuery("body").append("<div id='tilesFullView'><img src='" + jQuery(this).attr('rel') + "' alt='' /></div>");
		jQuery("#tilesFullView").css("top", (e.pageY - xOffset) + "px").css("left", (e.pageX + yOffset) + "px").fadeIn("fast");
	}, function() {
		jQuery("#tilesFullView").remove();
	});
	
	jQuery(".tiles").mousemove(function(e) {
		jQuery("#tilesFullView").css("top", (e.pageY - xOffset) + "px").css("left", (e.pageX + yOffset) + "px");
	});


});


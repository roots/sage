/* global confirm, redux_opts */

jQuery(document).ready(function() {

	// On label click, change the input and class
	jQuery('.redux-image-select label img, .redux-image-select label .tiles').click(function(e) {
		var id = jQuery(this).closest('label').attr('for');
		jQuery(this).parents("fieldset:first").find('.redux-image-select-selected').removeClass('redux-image-select-selected');
		jQuery(this).closest('label').find('input[type="radio"]').prop('checked');
		if (jQuery(this).closest('label').hasClass('redux-image-select-presets')) { // If they clicked on a preset, import!
			e.preventDefault();
			var presets = jQuery(this).closest('label').find('input');
			var data = presets.data('presets');
			if (presets !== undefined && presets !== null) {
				var answer = confirm(redux_opts.preset_confirm);
				if (answer) {
					jQuery('label[for="' + id + '"]').addClass('redux-image-select-selected');
					window.onbeforeunload = null;
					jQuery('#import-code-value').val(JSON.stringify(data));
					jQuery('#redux-import').click();
				}
			} else {}
			return false;
		} else {
            redux_change(jQuery(this).closest('label').find('input[type="radio"]'));
			jQuery('label[for="' + id + '"]').addClass('redux-image-select-selected');
		}
	});

	// Used to display a full image preview of a tile/pattern
	jQuery('.tiles').tipsy({
		gravity: jQuery.fn.tipsy.autoWE,
		fade: true,
		html: true,
		title : function(){
			return "<img src='" + jQuery(this).attr('rel') + "' style='max-width:150px;' alt='' />";
		},
		opacity: 1,
	});
	/*var xOffset = 10; // these 2 variable determine the popup's distance from the cursor
	var yOffset = 30;
	jQuery(".tiles").hover(function(e) {
		jQuery("body").append("<div id='tilesFullView'><img src='" + jQuery(this).attr('rel') + "' alt='' /></div>");
		jQuery("#tilesFullView").css("top", (e.pageY - xOffset) + "px").css("left", (e.pageX + yOffset) + "px").fadeIn("fast");
	}, function() {
		jQuery("#tilesFullView").remove();
	});
	
	jQuery(".tiles").mousemove(function(e) {
		jQuery("#tilesFullView").css("top", (e.pageY - xOffset) + "px").css("left", (e.pageX + yOffset) + "px");
	});*/


});

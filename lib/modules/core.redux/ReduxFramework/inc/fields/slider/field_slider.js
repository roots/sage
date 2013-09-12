/* global redux_change, reduxSliders */
jQuery(document).ready(function() {
	jQuery('.redux_slider').each(function() {
		//slider init
		var slider = reduxSliders[jQuery(this).attr('rel')];
	
		jQuery(this).slider({
			value: parseInt(slider.val, null),
			min: parseInt(slider.min, null),
			max: parseInt(slider.max, null),
			step: parseInt(slider.step, null),
			range: "min",
			slide: function(event, ui) {
				var input = jQuery("#" + slider.id);
				input.val(ui.value);
				redux_change(input);
			}
		});

		// Limit input for negative
		var neg = false;
		if (parseInt(slider.min, null) < 0) {
			neg = true;
		}

		jQuery(".slider-input").numeric({
			negative: neg,
			min: slider.min,
			max: slider.max
		});

	});
	
	// Update the slider from the input and vice versa
	jQuery(".slider-input").keyup(function() {

		var slider = reduxSliders[jQuery(this).attr('id')];
		var value = parseInt(jQuery(this).val(), null);
		if (value > slider.max) {
			value = slider.max;
		} else if (value < slider.min) {
			value = slider.min;
		}

		jQuery('#' + slider.id + '-slider').slider("value", value);
		jQuery("#" + slider.id).val(value);

	});
});
/* global redux_change */
jQuery.noConflict();
jQuery(document).ready(function() {
	jQuery('.redux_slider').each(function() {
		//slider init
		var id = jQuery(this).attr('id');
		var sliderParam = id + 'Param';
		sliderParam = sliderParam.split("-");
		sliderParam = window[sliderParam[0] + 'Param'];
		jQuery(this).slider({
			value: parseInt(sliderParam.val, null),
			min: parseInt(sliderParam.min, null),
			max: parseInt(sliderParam.max, null),
			step: parseInt(sliderParam.step, null),
			range: "min",
			slide: function(event, ui) {
				var input = jQuery("#" + sliderParam.id);
				input.val(ui.value);
				redux_change(input);
			}
		});
		// Limit input for negative
		var neg = false;
		if (parseInt(sliderParam.min, null) < 0) {
			neg = true;
		}
		jQuery(".slider-input").numeric({
			negative: neg,
			min: sliderParam.min,
			max: sliderParam.max
		});
	});
	// Update the slider from the input and vice versa
	jQuery(".slider-input").keyup(function() {
		var sliderParam = window[jQuery(this).attr('id') + 'Param'];
		var value = parseInt(jQuery(this).val(), null);
		if (value > sliderParam.max) {
			value = sliderParam.max;
		} else if (value < sliderParam.min) {
			value = sliderParam.min;
		}
		jQuery('#' + sliderParam.id + '-slider').slider("value", value);
		jQuery("#" + sliderParam.id).val(value);
	});
});
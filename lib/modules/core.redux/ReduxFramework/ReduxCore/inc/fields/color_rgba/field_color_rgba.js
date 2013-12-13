/*global jQuery, document, redux_change */
(function($){
	'use strict';

	$.redux = $.redux || {};

	var tcolour; 

	$(document).ready(function(){
		$.redux.color_rgba();
	});

	$.redux.color_rgba = function(){
		$('.redux-color_rgba-init').minicolors({
			animationSpeed: 50,
			animationEasing: 'swing',
			inline: false,
			letterCase: 'lowercase',
			position: 'bottom left',
			theme: 'default',
			opacity: true,
			//theme: 'bootstrap',
			change: function(hex, opacity) {
				//console.log(hex + ' - ' + opacity);
				redux_change($(this));
				//$('#' + this.id + '-transparency').removeAttr('checked');
				//console.group("Trace"); 
				//console.log( $('#' + this.id + '-transparency').prop('checked') );
				//console.groupEnd();
				$('#' +$(this).data('id')+ '-transparency').removeAttr('checked');
				$('#' +$(this).data('id')+ '-alpha').val(opacity);
				//console.log('#' + this.id + '-transparency');
				//console.log($(this).minicolors('rgbaString'));
			}
		});

		$('.redux-color_rgba').on('focus', function() {
			$(this).data('oldcolor', $(this).val());
		});

		$('.redux-color_rgba').on('keyup', function() {
			var value = $(this).val();
			var color = redux_color_rgba_validate(this);
			var id = '#' + $(this).attr('id');
			if (value === "transparent") {
				$('#' + $(this).data('id')).parent().parent().find('.minicolors-swatch-color').attr('style', '');
				$(id + '-transparency').attr('checked', 'checked');
			} else {
				$(id + '-transparency').removeAttr('checked');
				if (color && color !== $(this).val()) {
					$(this).val(color);
				}
			}
		});

		// Replace and validate field on blur
		$('.redux-color_rgba').on('blur', function() {
			var value = $(this).val();
			var id = '#' + $(this).attr('id');
			if (value === "transparent") {
				$('#' + $(this).data('id')).parent().parent().find('.minicolors-swatch-color').attr('style', '');
				$(id + '-transparency').attr('checked', 'checked');
			} else {
				if (redux_color_validate(this) === value) {
					if (value.indexOf("#") !== 0) {
						$(this).val($(this).data('oldcolor'));
					}
				}
				$(id + '-transparency').removeAttr('checked');
			}
		});

		// Store the old valid color on keydown
		$('.redux-color_rgba').on('keydown', function() {
			$(this).data('oldkeypress', $(this).val());
		});

				// When transparency checkbox is clicked
		$('.color_rgba-transparency').on('click', function() {
			if ($(this).is(":checked")) {
				$('#' + $(this).data('id')).val('transparent');
				$('#' + $(this).data('id')).parent().parent().find('.minicolors-swatch-color').attr('style', '');
			} else {
				if ($('#' + $(this).data('id')).val() === 'transparent') {
					$('#' + $(this).data('id')).val('');
				}
			}
		});
	};

})(jQuery);



// Run the validation
function redux_color_rgba_validate(field) {
	var value = jQuery(field).val();
	/*
	if (colourNameToHex(value) !== value.replace('#', '')) {
		return colourNameToHex(value);
	}
	*/
	return value;
}

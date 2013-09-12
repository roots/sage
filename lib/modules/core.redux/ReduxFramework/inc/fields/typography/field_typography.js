/* global redux_change */
/**
 * Typography
 * Dependencies		: google.com, jquery
 * Feature added by : Dovy Paukstys - http://simplerain.com/
 * Date				: 06.14.2013
 */
jQuery.noConflict();
/** Fire up jQuery - let's dance!
 */
jQuery(document).ready(function($) {

	Object.size = function(obj) {
		var size = 0,
			key;
		for (key in obj) {
			if (obj.hasOwnProperty(key)) {
				size++;
			}
		}
		return size;
	};

	function typographySelect(selector) {
		var mainID = jQuery(selector).parents('.redux-typography-container:first').attr('id');
		if ($(selector).hasClass('redux-typography-family')) {
			//$('#' + mainID + ' .typography-style span').text('');
			//$('#' + mainID + ' .typography-script span').text('');
		}
		// Set all the variables to be checked against
		var family = $('#' + mainID + ' select.redux-typography-family').val();
		var size = $('#' + mainID + ' .redux-typography-size').val();
		var height = $('#' + mainID + ' .redux-typography-height').val();
		var style = $('#' + mainID + ' select.redux-typography-style').val();
		var script = $('#' + mainID + ' select.redux-typography-script').val();
		var color = $('#' + mainID + ' .redux-typography-color').val();
		var units = $('#' + mainID).data('units');
		var option = $('#' + mainID + ' .redux-typography-family option:selected');
		//$('#' + mainID + ' select.redux-typography-style').val('');
		//$('#' + mainID + ' select.redux-typography-script').val('');
		var google = option.data('google'); // Check if font is a google font
		// Page load. Speeds things up memory wise to offload to client
		if (!$('#' + mainID).hasClass('typography-initialized')) {
			style = $('#' + mainID + ' select.redux-typography-style').data('value');
			script = $('#' + mainID + ' select.redux-typography-script').data('value');
			if (style !== "") {
				style = String(style);
			}
			if (typeof(script) !== undefined) {
				script = String(script);
			}
			$('#' + mainID).addClass('typography-initialized');
		}
		// Get the styles and such from the font
		var details = jQuery.parseJSON(decodeURIComponent(option.data('details')));
		// If we changed the font
		if ($(selector).hasClass('redux-typography-family')) {
			var html = '<option value=""></option>';
			if (google) { // Google specific stuff
				var selected = "";
				$.each(details.variants, function(index, variant) {
					if (variant.id === style || Object.size(details.variants) === 1) {
						selected = ' selected="selected"';
						style = variant.id;
					} else {
						selected = "";
					}
					html += '<option value="' + variant.id + '"' + selected + '>' + variant.name.replace(/\+/g, " ") + '</option>';
				});
				$('#' + mainID + ' .redux-typography-style').html(html);
				selected = "";
				html = '<option value=""></option>';
				$.each(details.subsets, function(index, subset) {
					if (subset.id === script || Object.size(details.subsets) === 1) {
						selected = ' selected="selected"';
						script = subset.id;
					} else {
						selected = "";
					}
					html += '<option value="' + subset.id + '"' + selected + '>' + subset.name.replace(/\+/g, " ") + '</option>';
				});
				$('#' + mainID + ' .redux-typography-script').html(html);
			} else {
				if (details) {
					$.each(details, function(index, value) {
						if (index === style || index === "normal") {
							selected = ' selected="selected"';
							$('#' + mainID + ' .typography-style .select2-chosen').text(value);
						} else {
							selected = "";
						}
						html += '<option value="' + index + '"' + selected + '>' + value.replace('+', ' ') + '</option>';
					});
					$('#' + mainID + ' .redux-typography-style').html(html);
				}
			}
		}
		
		// Check if the selected value exists. If not, empty it. Else, apply it.
		if ($('#' + mainID + " select.redux-typography-style option[value='" + style + "']").length === 0) {
			style = "";
			$('#' + mainID + ' select.redux-typography-style').val('');
		} else if (style === "400") {
			$('#' + mainID + ' select.redux-typography-style').val(style);
		}
		if ($('#' + mainID + " select.redux-typography-script option[value='" + script + "']").length === 0) {
			script = "";
			$('#' + mainID + ' select.redux-typography-script').val('');
		}

		var _linkclass = 'style_link_' + mainID;
		if (family) { //if var exists and isset
			//Check if selected is not equal with "Select a font" and execute the script.
			if (family !== 'none' && family !== '') {
				//remove other elements crested in <head>
				$('.' + _linkclass).remove();
				//replace spaces with "+" sign
				var the_font = family.replace(/\s+/g, '+');
				if (google) {
					//add reference to google font family
					var link = 'http://fonts.googleapis.com/css?family=' + the_font;
					if (style) {
						link += ':' + style.replace(/\-/g, " ");
					}
					if (script) {
						link += '&subset=' + script;
					}
					$('head').append('<link href="' + link + '" rel="stylesheet" type="text/css" class="' + _linkclass + '">');
					$('#' + mainID + ' .redux-typography-google').val(true);
				} else {
					$('#' + mainID + ' .redux-typography-google').val(false);
				}
				$('#' + mainID + ' .typography-preview').css('font-size', size + units);
				$('#' + mainID + ' .typography-preview').css('font-style', "normal");
				if (style !== null && style.indexOf("-") !== -1) {
					var n = style.split("-");
					$('#' + mainID + ' .typography-preview').css('font-weight', n[0]);
					$('#' + mainID + ' .typography-preview').css('font-style', n[1]);
				} else {
					if (!google) {
						$('#' + mainID + ' .typography-preview').css('font-weight', style);
					}
					$('#' + mainID + ' .typography-preview').css('font-style', style);
				}
				//show in the preview box the font
				$('#' + mainID + ' .typography-preview').css('font-family', family + ', sans-serif');
			} else {
				//if selected is not a font remove style "font-family" at preview box
				$('#' + mainID + ' .typography-preview').css('font-family', '');
			}
			if (height) {
				$('#' + mainID + ' .typography-preview').css('line-height', height + units);
			} else {
				$('#' + mainID + ' .typography-preview').css('line-height', size + units);
			}
			$('#' + mainID + ' .typography-preview').css('color', color);
		}
		$('#' + mainID + ' .typography-style .select2-chosen').text($('#' + mainID + ' .redux-typography-style option:selected').text());
		$('#' + mainID + ' .typography-script .select2-chosen').text($('#' + mainID + ' .redux-typography-script option:selected').text());
	}
	//init for each element
	jQuery('.redux-typography-container').each(function() {
		var family = jQuery(this).find('.redux-typography-family');
		if (family.data('value') !== "") {
			jQuery(family).val(family.data('value'));
		}
		typographySelect(family);
	});
	//init when value is changed
	jQuery('.redux-typography').on('change', function() {
		typographySelect(this);
	});
	//init when value is changed
	jQuery('.redux-typography-size, .redux-typography-height').keyup(function() {
		typographySelect(this);
	});
	// Have to redeclare the wpColorPicker to get a callback function
	$('.redux-typography-color').wpColorPicker({
		change: function(event, ui) {
			redux_change(jQuery(this));
			jQuery(this).val(ui.color.toString());
			typographySelect(jQuery(this));
		},
	});
	jQuery(".redux-typography-size, .redux-typography-height").numeric({
		negative: false
	});
	//jQuery(".redux-typography-family, .redux-typography-style, .redux-typography-script").select2({
	jQuery(".redux-typography-family").select2({
		width: 'resolve',
		triggerChange: true,
		allowClear: true
	});
});
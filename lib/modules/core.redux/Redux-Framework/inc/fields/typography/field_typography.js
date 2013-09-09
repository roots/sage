/* global redux_change */

jQuery.noConflict();
/** Fire up jQuery - let's dance!
 */
jQuery(document).ready(function($) {
	Object.size = function(obj) {
		var size = 0,
			key;
		for (key in obj) {
			if (obj.hasOwnProperty(key)) { size++; }
		}
		return size;
	};
	/**
	 * Google Fonts
	 * Dependencies			: google.com, jquery
	 * Feature added by : Dovy Paukstys - http://simplerain.com/
	 * Date							: 06.14.2013
	 */

	function typographySelect(mainID, selector) {
		if ($(selector).hasClass('redux-typography-family')) {
			//$('#' + mainID + ' .typography-style span').text('');
			//$('#' + mainID + ' .typography-script span').text('');
			//$('#' + mainID + ' .redux-typography-style').val('');
			//$('#' + mainID + ' .redux-typography-script').val('');
		}
		var family = $('#' + mainID + ' select.redux-typography-family ').val();
		var size = $('#' + mainID + ' .redux-typography-size').val();
		var height = $('#' + mainID + ' .redux-typography-height').val();
		var style = $('#' + mainID + ' select.redux-typography-style').val();
		var script = $('#' + mainID + ' select.redux-typography-script').val();
		var color = $('#' + mainID + ' .redux-typography-color').val();
		var units = $('#' + mainID).data('units');
		var option = $('#' + mainID + ' .redux-typography-family option:selected');
		console.log(jQuery.parseJSON(decodeURIComponent(option.data('details'))));
		var google = option.data('google');
		var details = jQuery.parseJSON(decodeURIComponent(option.data('details')));
		var selected = "";
		var html = "";

		if ($(selector).hasClass('redux-typography-family')) {
			html = '<option value=""></option>';
			for (var i = 0; i < Object.size(details.variants); i++) {
				if (details.variants[i] === null) {
					continue;
				}
				console.log(details.variants[i]);
				if ( (typeof(details.variants[i].id) !== undefined && details.variants[i] && details.variants[i].id === style) || Object.size(details.variants) === 1) {
					selected = ' selected="selected"';
					$('#' + mainID + ' .typography-style .select2-chosen').text(details.variants[i].name.replace('+', ' '));
				} else {
					selected = "";
				}
				html += '<option value="' + details.variants[i].id + '"' + selected + '>' + details.variants[i].name.replace(/\+/g, " ") + '</option>';
			}
			$('#' + mainID + ' .redux-typography-style').html(html);
			html = '<option value=""></option>';
			for (i = 0; i <= Object.size(details.subsets); i++) {
				if (typeof(details.subsets[i]) === undefined) {
					continue;
				}
				if (details.subsets[i].id === script || Object.size(details.subsets) === 1) {
					selected = ' selected="selected"';
					$('#' + mainID + ' .typography-script .select2-chosen').text(details.subsets[i].name.replace('+', ' '));
				} else {
					selected = "";
				}
				html += '<option value="' + details.subsets[i].id + '"' + selected + '>' + details.subsets[i].name.replace(/\+/g, " ") + '</option>';
			}
			$('#' + mainID + ' .redux-typography-script').html(html);
		} 

		/*else {
			if ($(selector).hasClass('redux-typography-family')) {
				$('#' + mainID + ' .redux-typography-script').html('');
				$('#' + mainID + ' .redux-typography-style').html('');
				$('#' + mainID + ' .typography-style .select2-chosen').text('');
				$('#' + mainID + ' .typography-script .select2-chosen').text('');
				html = '<option value=""></option>';
				$.each(details, function(index, value) {
					if (index === "normal") {
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
		*/
		var _linkclass = 'style_link_' + mainID;
		if (family) { //if var exists and isset
			//Check if selected is not equal with "Select a font" and execute the script.
			if (family !== 'none' && family !== '') {
				//remove other elements crested in <head>
				$('.' + _linkclass).remove();
				//replace spaces with "+" sign
				var the_font = family.replace(/\s+/g, '+');
				if ($('#' + mainID + ' .redux-typography-family option:selected').data('google')) {
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
				if (style.indexOf("-") !== -1) {
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
		var face = jQuery('#' + jQuery(this).attr('id') + " .redux-typography-family");
		if (face.data('value') !== "") {
			jQuery(face).val(face.data('value'));
		}
		typographySelect(jQuery(this).attr('id'), $(this));
	});
	//init when value is changed
	jQuery('.redux-typography').change(function() {
		typographySelect(jQuery(this).closest('.redux-typography-container').attr('id'), $(this));
	});
	//init when value is changed
	jQuery('.redux-typography-size, .redux-typography-height').keyup(function() {
		typographySelect(jQuery(this).closest('.redux-typography-container').attr('id'), $(this));
	});
	// Have to redeclare the wpColorPicker to get a callback function
	$('.redux-typography-color').wpColorPicker({
		change: function(event, ui) {
			redux_change(jQuery(this));
			jQuery(this).val(ui.color.toString());
			typographySelect(jQuery(this).closest('.redux-typography-container').attr('id'), $(this));
		},
	});
	jQuery(".redux-typography-size, .redux-typography-height").numeric({
		negative: false
	});
jQuery(".redux-typography-family, .redux-typography-style, .redux-typography-script").select2({
//	jQuery(".redux-typography-family").select2({
		width: 'resolve',
		triggerChange: true,
		allowClear: true
	});
});
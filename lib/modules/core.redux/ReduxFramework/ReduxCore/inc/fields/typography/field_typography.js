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
		var familyBackup = $('#' + mainID + ' select.redux-typography-family-backup').val();
		var size = $('#' + mainID + ' .redux-typography-size').val();
		var height = $('#' + mainID + ' .redux-typography-height').val();
		var word = $('#' + mainID + ' .redux-typography-word').val(); // New Word-Spacing
		var letter = $('#' + mainID + ' .redux-typography-letter').val(); // New Letter-Spacing
		var style = $('#' + mainID + ' select.redux-typography-style').val();
		var script = $('#' + mainID + ' select.redux-typography-subsets').val();
		var color = $('#' + mainID + ' .redux-typography-color').val();
		var units = $('#' + mainID).data('units');
		var option = $('#' + mainID + ' .redux-typography-family option:selected');
		var output = family;
		//$('#' + mainID + ' select.redux-typography-style').val('');
		//$('#' + mainID + ' select.redux-typography-subsets').val('');
		var google = option.data('google'); // Check if font is a google font
		// Page load. Speeds things up memory wise to offload to client
		if (!$('#' + mainID).hasClass('typography-initialized')) {
			style = $('#' + mainID + ' select.redux-typography-style').data('value');
			script = $('#' + mainID + ' select.redux-typography-subsets').data('value');
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
		$('#' + mainID + ' .redux-typography-font-options').val(decodeURIComponent(option.data('details')));
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
				if ( typeof( familyBackup ) !== "undefined" && familyBackup !== "" ) {
					output += ', '+familyBackup;
				}
				
				$('#' + mainID + ' .redux-typography-subsets').html(html);
				$('#' + mainID + ' .redux-typography-subsets').fadeIn('fast');
				$('#' + mainID + ' .typography-family-backup').fadeIn('fast');
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
					$('#' + mainID + ' .redux-typography-subsets').fadeOut('fast');
					$('#' + mainID + ' .typography-family-backup').fadeOut('fast');
				}
			}
		} else if ( $(selector).hasClass('redux-typography-family-backup') && familyBackup !== "") {
			$('#' + mainID + ' .redux-typography-font-family').val(output);
		}
		
		// Check if the selected value exists. If not, empty it. Else, apply it.
		if ($('#' + mainID + " select.redux-typography-style option[value='" + style + "']").length === 0) {
			style = "";
			$('#' + mainID + ' select.redux-typography-style').val('');
		} else if (style === "400") {
			$('#' + mainID + ' select.redux-typography-style').val(style);
		}
		if ($('#' + mainID + " select.redux-typography-subsets option[value='" + script + "']").length === 0) {
			script = "";
			$('#' + mainID + ' select.redux-typography-subsets').val('');
		}

		var _linkclass = 'style_link_' + mainID;
	
		//remove other elements crested in <head>
		$('.' + _linkclass).remove();
		if (family !== null) {
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
		}

		$('#' + mainID + ' .typography-preview').css('font-size', size + units);
		$('#' + mainID + ' .typography-preview').css('font-style', "normal");
		
		// Weight and italic
		if (style.indexOf("italic") !== -1) {
			$('#' + mainID + ' .typography-preview').css('font-style', 'italic');
			$('#' + mainID + ' .typography-font-style').val('italic');
			style = style.replace('italic', '');
		} else {
			$('#' + mainID + ' .typography-font-style').val('');
		}
		$('#' + mainID + ' .typography-font-weight').val(style);
		$('#' + mainID + ' .typography-preview').css('font-weight', style);

		//show in the preview box the font
		$('#' + mainID + ' .typography-preview').css('font-family', family + ', sans-serif');

		if (family === 'none' && family === '') {
			//if selected is not a font remove style "font-family" at preview box
			$('#' + mainID + ' .typography-preview').css('font-family', '');
		}
		if (!height) {
			height = size;
		}
		$('#' + mainID + ' .typography-preview').css('line-height', height + units);
		$('#' + mainID + ' .typography-preview').css('word-spacing', word + units);
		$('#' + mainID + ' .typography-preview').css('letter-spacing', letter + units);
		if( size === '' ){
			$('#' + mainID + ' .typography-font-size').val( '' );
		}else{
			$('#' + mainID + ' .typography-font-size').val(size + units);
		}
		if( height === '' ){
			$('#' + mainID + ' .typography-line-height').val( '' );
		}else{
			$('#' + mainID + ' .typography-line-height').val(height + units);
		}
		$('#' + mainID + ' .typography-word-spacing').val(word + units);
		$('#' + mainID + ' .typography-letter-spacing').val(letter + units);

		if (color) {
			$('#' + mainID + ' .typography-preview').css('color', color);
			$('#' + mainID + ' .typography-preview').css('background-color', getContrastColour(color));	
		}
			
		$('#' + mainID + ' .redux-typography-font-family').val(output);
		$('#' + mainID + ' .typography-style .select2-chosen').text($('#' + mainID + ' .redux-typography-style option:selected').text());
		$('#' + mainID + ' .typography-script .select2-chosen').text($('#' + mainID + ' .redux-typography-subsets option:selected').text());
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
	jQuery('.redux-typography-size, .redux-typography-height, .redux-typography-word, .redux-typography-letter').keyup(function() {
		typographySelect(this);
	});
	// Have to redeclare the wpColorPicker to get a callback function
	$('.redux-typography-color').wpColorPicker({
		change: function(event, ui) {
			redux_change(jQuery(this));
			jQuery(this).val(ui.color.toString());
			typographySelect(jQuery(this));
		}
	});
	jQuery(".redux-typography-size, .redux-typography-word, .redux-typography-letter").numeric({
		allowMinus: false,
	});
	jQuery(".redux-typography-height").numeric({
		allowMinus: true,
	});		
	//jQuery(".redux-typography-family, .redux-typography-style, .redux-typography-subsets").select2({
	jQuery(".redux-typography-family, .redux-typography-family-backup").select2({
		width: 'resolve',
		triggerChange: true,
		allowClear: true
	});
});

/* global redux_change, wp */

(function($){
	"use strict";
    
    $.reduxBackground = $.reduxBackground || {};
	
    $(document).ready(function () {
         $.reduxBackground.init();
    });

	/**
	* Redux Background
	* Dependencies		: jquery, wp media uploader
	* Feature added by	: Dovy Paukstys
	* Date				: 07 Jan 2014
	*/
    $.reduxBackground.init = function(){
		// Remove the image button
		$('.redux-container-background .remove-image, .redux-container-background .remove-file').unbind('click').on('click', function(e) {
			$.reduxBackground.removeImage( $(this).parents('fieldset.redux-field:first') );
			$.reduxBackground.preview($(this));
			return false;
		});

		// Upload media button
		$('.redux-container-background .background_upload_button').unbind().on('click', function( event ) {
			$.reduxBackground.addImage( event, $(this).parents('fieldset.redux-field:first') );
		});

		$('.redux-background-input').on('change', function() {
			$.reduxBackground.preview($(this));
		});

		$('.redux-container-background .redux-color').wpColorPicker({
			change: function(u, ui) {
				redux_change($(this));
				$('#' + u.target.id + '-transparency').removeAttr('checked');
				$(this).val( ui.color.toString() );
				$.reduxBackground.preview($(this));
			}
		});
		

    };

    // Update the background preview
    $.reduxBackground.preview = function(selector) {
		var parent = selector.parents('.redux-container-background:first');
		var preview = $(parent).find('.body-background_previewer');

		if (!preview) { // No preview present
			return;
		}

		var split = parent.data('id')+'][';
		var css = 'height:'+preview.height()+'px;';
		$(parent).find('.redux-background-input').each(function() {
			var data = $(this).serializeArray();
			data = data[0];
			if (data && data.name.indexOf('background]') != -1) {
				if (data.value !== "") {
					data.name = data.name.split(split);
					data.name = data.name[1].replace(']', '');
					if (data.name == "background-image") {
						css += data.name+':url("'+data.value+'");';
					} else {
						css += data.name+':'+data.value+';';	
					}					
				}
				
			}
		});

		preview.attr('style', css).fadeIn();

	};

    // Add a file via the wp.media function
    $.reduxBackground.addImage = function (event, selector) {

		event.preventDefault();

		var frame;
		var jQueryel = jQuery(this);

		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
		}

		// Create the media frame.
		frame = wp.media({
			multiple: false,
			library: {
				//type: 'image' //Only allow images
			},
			// Set the title of the modal.
			title: jQueryel.data('choose'),

			// Customize the submit button.
			button: {
				// Set the text of the button.
				text: jQueryel.data('update')
				// Tell the button not to close the modal, since we're
				// going to refresh the page when the image is selected.

			}
		});

		// When an image is selected, run a callback.
		frame.on( 'select', function() {
			// Grab the selected attachment.
			var attachment = frame.state().get('selection').first();
			frame.close();

			console.log(attachment.attributes.type);

			if ( attachment.attributes.type !== "image") {
				return;
			}

			selector.find('.upload').val(attachment.attributes.url);
			selector.find('.upload-id').val(attachment.attributes.id);
			selector.find('.upload-height').val(attachment.attributes.height);
			selector.find('.upload-width').val(attachment.attributes.width);
			redux_change( jQuery(selector).find( '.upload-id' ) );
			var thumbSrc = attachment.attributes.url;
			if (typeof attachment.attributes.sizes !== 'undefined' && typeof attachment.attributes.sizes.thumbnail !== 'undefined') {
				thumbSrc = attachment.attributes.sizes.thumbnail.url;
			} else if ( typeof attachment.attributes.sizes !== 'undefined' ) {
				var height = attachment.attributes.height;
				for (var key in attachment.attributes.sizes) {
					var object = attachment.attributes.sizes[key];
					if (object.height < height) {
						height = object.height;
						thumbSrc = object.url;
					}
				}
			} else {
				thumbSrc = attachment.attributes.icon;
			}
			selector.find('.upload-thumbnail').val(thumbSrc);
			if ( !selector.find('.upload').hasClass('noPreview') ) {
				selector.find('.screenshot').empty().hide().append('<img class="redux-option-image" src="' + thumbSrc + '">').slideDown('fast');
			}
			//selector.find('.media_upload_button').unbind();
			selector.find('.remove-image').removeClass('hide');//show "Remove" button
			selector.find('.redux-background-input-properties').slideDown();
			$.reduxBackground.preview(selector.find('.upload'));
		});

		// Finally, open the modal.
		frame.open();
	};

    // Update the background preview
    $.reduxBackground.removeImage = function(selector) {

		// This shouldn't have been run...
		if (!selector.find('.remove-image').addClass('hide')) {
			return;
		}
		selector.find('.remove-image').addClass('hide');//hide "Remove" button
		selector.find('.upload').val('');
		selector.find('.upload-id').val('');
		selector.find('.upload-height').val('');
		selector.find('.upload-width').val('');
		redux_change( jQuery(selector).find( '.upload-id' ) );
		selector.find('.redux-background-input-properties').hide();
		var screenshot = selector.find('.screenshot');
		
		// Hide the screenshot
		screenshot.slideUp();

		selector.find('.remove-file').unbind();
		// We don't display the upload button if .upload-notice is present
		// This means the user doesn't have the WordPress 3.5 Media Library Support
		if ( jQuery('.section-upload .upload-notice').length > 0 ) {
			jQuery('.background_upload_button').remove();
		}

		//$.reduxBackground.preview(selector);

    };    

})(jQuery);

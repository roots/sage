/* global redux_change, wp */

jQuery(document).ready(function($){

	/**
		* Media Uploader
		* Dependencies		: jquery, wp media uploader
		* Feature added by	: Smartik - http://smartik.ws/
		* Date				: 05.28.2013
	*/

	// Add a file via the wp.media function
	function redux_add_file(event, selector) {

		var frame;
		var $el = $(this);

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
		}

		// Create the media frame.
		frame = wp.media({
			multiple: false,
			library: {
				type: 'image' //Only allow images
			},
			// Set the title of the modal.
			title: $el.data('choose'),

			// Customize the submit button.
			button: {
				// Set the text of the button.
				text: $el.data('update')
				// Tell the button not to close the modal, since we're
				// going to refresh the page when the image is selected.

			}
		});

		// When an image is selected, run a callback.
		frame.on( 'select', function() {

			// Grab the selected attachment.
			var attachment = frame.state().get('selection').first();
			frame.close();
			if (attachment.attributes.type !== "image") {
				return;
			}
			selector.find('.upload').val(attachment.attributes.url);
			selector.find('.upload-id').val(attachment.attributes.id);
			selector.find('.upload-height').val(attachment.attributes.height);
			selector.find('.upload-width').val(attachment.attributes.width);
			if ( attachment.attributes.type === 'image' && !selector.find('.upload').hasClass('noPreview') ) {
				selector.find('.screenshot').empty().hide().append('<img class="redux-option-image" src="' + attachment.attributes.url + '">').slideDown('fast');
			}
			selector.find('.media_upload_button').unbind();
			selector.find('.remove-image').removeClass('hide');//show "Remove" button
			selector.find('.redux-background-properties').slideDown();

		});

		// Finally, open the modal.
		frame.open();
	}


	// Function to remove the image on click. Still requires a save
	function redux_remove_file(selector) {

		// This shouldn't have been run...
		if (!selector.find('.remove-image').addClass('hide')) {
			return;
		}

		redux_change(jQuery('#'+selector.attr('rel')));
		selector.find('.remove-image').addClass('hide');//hide "Remove" button
		selector.find('.upload').val('');
		selector.find('.upload-id').val('');
		selector.find('.upload-height').val('');
		selector.find('.upload-width').val('');
		selector.find('.redux-background-properties').hide();
		var screenshot = selector.find('.screenshot');
		
		// Hide the screenshot
		screenshot.slideUp();

		selector.find('.remove-file').unbind();
		// We don't display the upload button if .upload-notice is present
		// This means the user doesn't have the WordPress 3.5 Media Library Support
		if ( $('.section-upload .upload-notice').length > 0 ) {
			$('.media_upload_button').remove();
		}

	}

	// Remove the image button
	$('.remove-image, .remove-file').on('click', function() {
		redux_remove_file( $(this).parents('td') );
		redux_change($(this).parents('td:first').find('.upload'));
	});

	// Upload media button
	$('.media_upload_button').unbind('click').click( function( event ) {
		redux_add_file(event, $(this).parents('td'));
		redux_change($(this).parents('td:first').find('.upload'));
	});


});
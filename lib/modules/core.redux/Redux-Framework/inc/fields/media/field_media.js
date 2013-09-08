/* global redux_change, wp */

jQuery.noConflict();

/** Fire up jQuery - let's dance!
 */
jQuery(document).ready(function($){

	/**
	  * Media Uploader
	  * Dependencies		: jquery, wp media uploader
	  * Feature added by	: Smartik - http://smartik.ws/
	  * Date				: 05.28.2013
	  */
	function redux_add_file(event, selector) {

		var upload = $(".uploaded-file"), frame;
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
				text: $el.data('update'),
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
			if ( attachment.attributes.type === 'image' ) {
				selector.find('.screenshot').empty().hide().append('<img class="redux-option-image" src="' + attachment.attributes.url + '">').slideDown('fast');
			}
			selector.find('.media_upload_button').unbind();
			selector.find('.remove-image').removeClass('hide');//show "Remove" button
			selector.find('.redux-background-properties').slideDown();
			redux_file_bindings();
		});

		// Finally, open the modal.
		frame.open();
	}

	function redux_remove_file(selector) {

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
		//if (!screenshot.hasClass('min')) {
			screenshot.slideUp();
		//}
		selector.find('.remove-file').unbind();
		// We don't display the upload button if .upload-notice is present
		// This means the user doesn't have the WordPress 3.5 Media Library Support
		if ( $('.section-upload .upload-notice').length > 0 ) {
			$('.media_upload_button').remove();
		}
		redux_file_bindings();
	}

	function redux_file_bindings() {
		$('.remove-image, .remove-file').on('click', function() {
			redux_remove_file( $(this).parents('td') );
	});

	$('.media_upload_button').unbind('click').click( function( event ) {
		redux_add_file(event, $(this).parents('td'));
	});
    }

    redux_file_bindings();


});
jQuery.noConflict();

/** Fire up jQuery - let's dance!
 */
jQuery(document).ready(function($){

	/**
	  * Media Uploader
	  * Dependencies 	 : jquery, wp media uploader
	  * Feature added by : Smartik - http://smartik.ws/
	  * Date 			 : 05.28.2013
	  */
	function SimpleOptions_add_file(event, selector) {

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
			if (attachment.attributes.type != "image") {
				return;
			}
			//console.log(attachment.attributes);
			console.log(attachment.attributes);
			selector.find('.upload').val(attachment.attributes.url);
			selector.find('.upload-id').val(attachment.attributes.id);
			selector.find('.upload-height').val(attachment.attributes.height);
			selector.find('.upload-width').val(attachment.attributes.width);
			if ( attachment.attributes.type == 'image' ) {
				selector.find('.screenshot').empty().hide().append('<img class="sof-option-image" src="' + attachment.attributes.url + '">').slideDown('fast');
			}
			selector.find('.media_upload_button').unbind();
			selector.find('.remove-image').removeClass('hide');//show "Remove" button
			selector.find('.of-background-properties').slideDown();
			SimpleOptions_file_bindings();
		});

		// Finally, open the modal.
		frame.open();
	}

	function SimpleOptions_remove_file(selector) {
		if (!selector.find('.remove-image').addClass('hide')) {
			return;
		}
		sof_change();
		selector.find('.remove-image').addClass('hide');//hide "Remove" button
		selector.find('.upload').val('');
		selector.find('.of-background-properties').hide();
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
		SimpleOptions_file_bindings();
	}

	function SimpleOptions_file_bindings() {
		$('.remove-image, .remove-file').on('click', function() {
			SimpleOptions_remove_file( $(this).parents('td') );
	});

	$('.media_upload_button').unbind('click').click( function( event ) {
		SimpleOptions_add_file(event, $(this).parents('td'));
	});
    }

    SimpleOptions_file_bindings();


});
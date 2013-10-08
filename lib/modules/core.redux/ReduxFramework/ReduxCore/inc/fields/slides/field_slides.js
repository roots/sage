/* global redux_change, wp */

jQuery(document).ready(function () {

    jQuery('.redux-slides-remove').live('click', function () {
        redux_change(jQuery(this));
        jQuery(this).parent().siblings().find('input[type="text"]').val('');
        jQuery(this).parent().siblings().find('input[type="hidden"]').val('');
        jQuery(this).parents().eq(3).slideUp('medium', function () {
            jQuery(this).remove();
        });
    });

    jQuery('.redux-slides-add').click(function () {

        var newSlide = jQuery(this).prev().find('.redux-slides-accordion-group:last').clone(true);
        var slideCount = jQuery(newSlide).find('input[type="text"]').attr("name").match(/\d+/);
        var slideCount1 = slideCount*1 + 1;

        jQuery(newSlide).find('input[type="text"], input[type="hidden"], textarea').each(function(){

            jQuery(this).attr("name", jQuery(this).attr("name").replace(/\d+/, slideCount1) ).attr("id", jQuery(this).attr("id").replace(/\d+/, slideCount1) );
            jQuery(this).val('');
            if (jQuery(this).hasClass('slide-sort')){
                jQuery(this).val(slideCount1);
            }
        });

        jQuery(newSlide).find('.screenshot').removeAttr('style');
        jQuery(newSlide).find('.screenshot').addClass('hide');
        jQuery(newSlide).find('.screenshot a').attr('href', '');
        jQuery(newSlide).find('.remove-image').addClass('hide');
        jQuery(newSlide).find('.redux-slides-image').attr('src', '').removeAttr('id');
        jQuery(newSlide).find('h3').text('').append('<span class="redux-slides-header">New slide</span><span class="ui-accordion-header-icon ui-icon ui-icon-plus"></span>');
        jQuery(this).prev().append(newSlide);
    });

    jQuery('.slide-title').keyup(function(event) {
        var newTitle = event.target.value;
        jQuery(this).parents().eq(3).find('.redux-slides-header').text(newTitle);
    });

    jQuery(function () {
        jQuery("#redux-slides-accordion")
            .accordion({
                header: "> div > h3",
                collapsible: true,
                active: false,
                heightStyle: "content",
                icons: { "header": "ui-icon-plus", "activeHeader": "ui-icon-minus" }
            })
            .sortable({
                axis: "y",
                handle: "h3",
                stop: function (event, ui) {
                    // IE doesn't register the blur when sorting
                    // so trigger focusout handlers to remove .ui-state-focus
                    ui.item.children("h3").triggerHandler("focusout");
                    var inputs = jQuery('input.slide-sort');
                    inputs.each(function(idx) {
                        jQuery(this).val(idx);
                    });
                }
            });
    });



    // Add a file via the wp.media function
    function redux_add_file(event, selector) {

        var frame;
        var $el = jQuery(this);

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
            if ( attachment.attributes.type === 'image' ) {
                selector.find('.screenshot').empty().hide().append('<img class="redux-slides-image" src="' + attachment.attributes.url + '">').slideDown('fast');
            }
           // selector.find('.media_upload_button_slide').unbind();
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

        //redux_change(jQuery('#'+selector.attr('rel')));
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
        if ( jQuery('.section-upload .upload-notice').length > 0 ) {
            jQuery('.media_upload_button_slide').remove();
        }

    }

    // Remove the image button
    jQuery('.remove-image, .remove-file').unbind('click').click(function() {
        redux_remove_file( jQuery(this).parents().eq(2));
    });

    // Upload media button
    jQuery('.media_upload_button_slide').unbind('click').click( function( event ) {
        redux_add_file(event, jQuery(this).parents().eq(2));
    });


});
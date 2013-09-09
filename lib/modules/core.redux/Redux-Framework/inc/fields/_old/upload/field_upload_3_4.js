/*global jQuery, document, redux_upload, formfield:true, preview:true, tb_show, window, imgurl:true, tb_remove, $relid:true*/
/*
This is the uploader for wordpress before version 3.5
*/
jQuery(document).ready(function () {
    "use strict";

    /*
     *
     * Redux_Options_upload function
     * Adds media upload functionality to the page
     *
     */

    var header_clicked = false;
    var formfield;
    var preview;
    var $relid;
    var imgurl;

    jQuery("img[src='']").attr("src", redux_upload.url);

    jQuery('.redux-opts-upload').click(function () {
        header_clicked = true;
        formfield = jQuery(this).attr('rel-id');
        preview = jQuery(this).prev('img');
        tb_show('', 'media-upload.php?type=image&amp;post_id=0&amp;TB_iframe=true');
        return false;
    });

    jQuery('.redux-opts-upload-remove').click(function () {
        $relid = jQuery(this).attr('rel-id');
        jQuery('#' + $relid).val('');
        jQuery(this).prev().fadeIn('slow');
        jQuery(this).prev().prev().fadeOut('slow', function () { jQuery(this).attr("src", redux_upload.url); });
        jQuery(this).fadeOut('slow');
    });

    // Store original function
    window.original_send_to_editor = window.send_to_editor;

    window.send_to_editor = function (html) {
        if (header_clicked) {
            imgurl = jQuery('img', html).attr('src');
            jQuery('#' + formfield).val(imgurl).trigger('change');
            jQuery('#' + formfield).next().fadeIn('slow');
            jQuery('#' + formfield).next().next().fadeOut('slow');
            jQuery('#' + formfield).next().next().next().fadeIn('slow');
            jQuery(preview).attr('src', imgurl);
            tb_remove();
            header_clicked = false;
        } else {
            window.original_send_to_editor(html);
        }
    }
});

/*global jQuery*/
/*
 *
 * Redux_Options_radio_img function
 * Changes the radio select option, and changes class on images
 *
 */
function redux_radio_img_select(relid, labelclass) {
    jQuery(this).prev('input[type="radio"]').prop('checked');
    jQuery('.redux-radio-img-' + labelclass).removeClass('redux-radio-img-selected');
    jQuery('label[for="' + relid + '"]').addClass('redux-radio-img-selected');
}

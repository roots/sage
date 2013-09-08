/*global jQuery, document*/
jQuery(document).ready(function () {
    jQuery('.redux-opts-select-hide-below').each(function () {
        if (jQuery('option:selected', this).attr('data-allow') === 'false') {
            jQuery(this).closest('tr').next('tr').hide();
        }
    });

    jQuery('.redux-opts-select-hide-below').change(function () {
        var option = jQuery('option:selected', this);

        if (option.attr('data-allow') === 'false') {
            if (jQuery(this).closest('tr').next('tr').is(':visible')) {
                jQuery(this).closest('tr').next('tr').fadeOut('slow');
            }
        } else {
            if (jQuery(this).closest('tr').next('tr').is(':hidden')) {
                jQuery(this).closest('tr').next('tr').fadeIn('slow');
            }
        }
    });
});

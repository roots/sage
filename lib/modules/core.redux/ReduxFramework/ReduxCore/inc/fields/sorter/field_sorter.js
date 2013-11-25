/* global redux_change, redux_opts */
/*
 * Field Sorter jquery function
 * Based on
 * [SMOF - Slightly Modded Options Framework](http://aquagraphite.com/2011/09/slightly-modded-options-framework/)
 * Version 1.4.2
 */

jQuery(function() {
    /**	Sorter (Layout Manager) */
    jQuery('.sorter').each(function() {
        var id = jQuery(this).attr('id');
        jQuery('#' + id).find('ul').sortable({
            items: 'li',
            placeholder: "placeholder",
            connectWith: '.sortlist_' + id,
            opacity: 0.6,
            update: function() {
                jQuery(this).find('.position').each(function() {
                    var listID = jQuery(this).parent().attr('id');
                    var parentID = jQuery(this).parent().parent().attr('id');
                    parentID = parentID.replace(id + '_', '');
                    redux_change(jQuery(this));
                    var optionID = jQuery(this).parent().parent().parent().attr('id');
                    jQuery(this).prop("name", redux_opts.opt_name + '[' + optionID + '][' + parentID + '][' + listID + ']');
                });
            }
        });
    });

});
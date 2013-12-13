/*global jQuery, document, redux_change */

jQuery(document).ready(function() {

    jQuery( ".redux-sortable" ).sortable({
        handle: ".drag",
        placeholder: "ui-state-highlight",
        opacity: 0.7,
        update: function() {
            redux_change(jQuery(this));
        }        
    });

    jQuery( ".redux-sortable" ).disableSelection();	
	
	jQuery('.checkbox_sortable').on('click', function() {
		if (jQuery(this).is(":checked")) {
			jQuery('#'+jQuery(this).attr('rel')).val(1);
		} else {
			jQuery('#'+jQuery(this).attr('rel')).val('');
		}
	});

});
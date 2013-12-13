/* global redux_change */
jQuery(document).ready(function() {

    jQuery('.redux_spinner').each(function() {
        //slider init
        var spinner = redux.spinner[jQuery(this).attr('rel')];

        jQuery("#" + spinner.id).spinner({
            value: parseInt(spinner.val, null),
            min: parseInt(spinner.min, null),
            max: parseInt(spinner.max, null),
            step: parseInt(spinner.step, null),
            range: "min",
            slide: function(event, ui) {
                var input = jQuery("#" + spinner.id);
                input.val(ui.value);
                redux_change(input);
            }
        });

        // Limit input for negative
        var neg = false;
        if (parseInt(spinner.min, null) < 0) {
            neg = true;
        }

		jQuery("#" + spinner.id).numeric({
			allowMinus: neg,
			min: spinner.min,
			max: spinner.max
		});

    });

    // Update the slider from the input and vice versa
    jQuery(".spinner-input").keyup(function() {

        jQuery(this).addClass('spinnerInputChange');

    });

    function cleanSpinnerValue(value, selector, spinner) {

        if ( !selector.hasClass('spinnerInputChange') ) {
            return;
        }       
        selector.removeClass('spinnerInputChange');

        if (value === "" || value === null) {
            value = spinner.min;
        } else if (value >= parseInt(spinner.max)) {
            value = spinner.max;
        } else if (value <= parseInt(spinner.min)) {
            value = spinner.min;
        } else {
            value = Math.round(value / spinner.step) * spinner.step;
        }

        jQuery("#" + spinner.id).val(value);

    }

    // Update the spinner from the input and vice versa
    jQuery(".spinner-input").blur(function() {
//        cleanSpinnerValue(jQuery(this).val(), jQuery(this), redux.spinner[jQuery(this).attr('id')]);
    });
    jQuery(".spinner-input").focus(function() {
        cleanSpinnerValue(jQuery(this).val(), jQuery(this), redux.spinner[jQuery(this).attr('id')]);
    });

    jQuery('.spinner-input').typeWatch({
        callback:function(value){
            cleanSpinnerValue(value, jQuery(this), redux.spinner[jQuery(this).attr('id')]);
        },
        wait:500,
        highlight:false,
        captureLength:1
    });

});

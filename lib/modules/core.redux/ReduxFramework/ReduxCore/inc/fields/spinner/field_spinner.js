/* global redux_change, reduxSpinners */
jQuery(document).ready(function() {
    jQuery('.redux_spinner').each(function() {
        //slider init
        var spinner = reduxSpinners[jQuery(this).attr('rel')];

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

        jQuery(".spinner-input").numeric({
            negative: neg,
            min: spinner.min,
            max: spinner.max
        });

    });

    // Update the slider from the input and vice versa
    jQuery(".spinner-input").keyup(function() {

        jQuery(this).addClass('spinnerInputChange');

    });

    // Update the slider from the input and vice versa
    jQuery(".spinner-input").focus(function() {

        if (!jQuery(this).hasClass('spinnerInputChange')) {
            return;
        }
        jQuery(this).removeClass('spinnerInputChange');

        var spinner = reduxSpinners[jQuery(this).attr('id')];
        var value = jQuery(this).val();
        if (value > spinner.max) {
            value = spinner.max;
        } else if (value < spinner.min) {
            value = spinner.min;
        }

        jQuery('#' + spinner.id + '-spinner').spinner("value", value);
        jQuery("#" + spinner.id).val(value);

    });

    jQuery('.spinner-input').typeWatch({
        callback: function(value) {

            if (!jQuery(this).hasClass('spinnerInputChange')) {
                return;
            }
            jQuery(this).removeClass('spinnerInputChange');

            var spinner = reduxSpinners[jQuery(this).attr('id')];
            if (value > spinner.max) {
                value = spinner.max;
            } else if (value < spinner.min) {
                value = spinner.min;
            }

            jQuery('#' + spinner.id + '-spinner').spinner("value", value);
            jQuery("#" + spinner.id).val(value);

        },
        wait: 400,
        highlight: false,
        captureLength: 1
    });

});

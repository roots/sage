/* global confirm, redux_opts */

jQuery.noConflict();

jQuery(document).ready(function () {

  jQuery('.redux-image-select label').click(function () {

    var id = jQuery(this).attr('for');

    jQuery(this).parent().parent().find('.redux-image-select-selected').removeClass('redux-image-select-selected');

    jQuery(this).find('input[type="radio"]').prop('checked');
    jQuery('label[for="' + id + '"]').addClass('redux-image-select-selected');

  });

  jQuery('.redux-save-preset').on("click", function (e) {
    e.preventDefault();

    var presets = jQuery(this).parent().parent().find('.redux-presets label.redux-image-select-selected input[type="radio"]');
    var data = presets.data('presets');
    if (presets !== undefined && presets !== null) {
      var answer = confirm(redux_opts.preset_confirm);

      if (answer) {
        window.onbeforeunload = null;
        jQuery('#import-code-value').val(JSON.stringify(data));
        jQuery('#redux-import').click();
      }
    }

    return false;
  });
});
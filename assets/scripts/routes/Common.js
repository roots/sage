import 'jquery.scrollto';
import 'jquery.localscroll';
import 'scroll-depth';

export default {
  init() {
    // Track scroll depth to Google Analytics.
    $.scrollDepth();
    // Global smooth anchor scrolling.
    $.localScroll({ duration: 200 });
    // Automatically scroll to the confirmation message when submitting
    // a Gravity Form with AJAX.
    $(document).on('gform_confirmation_loaded', this.scrollToConfirmation.bind(this));
  },

  scrollToConfirmation() {
    $.scrollTo('.gform_confirmation_wrapper', {
      offset: { top: -150, left: 0 },
      duration: 200,
    });
  },

  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};

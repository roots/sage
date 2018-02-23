export default {
  init() {
    $('#order_review_heading').prependTo('#order_review');
    $('#createaccount').prependTo('.form-row.create-account');
    $('#ship-to-different-address-checkbox').prependTo($('#ship-to-different-address'))

  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  },
};

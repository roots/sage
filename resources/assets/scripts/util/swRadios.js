/*eslint-disable*/

export default class {
  static init() {
    const $form = $('form.variations_form');
    $form.on('change', '.sw-radios__input', () => {
      $form.trigger('change.wc-variation-form', { variationForm: $form });
      // $form.trigger('check_variations');
    });
  }

  static startEvents() {
    $(document).on('sw-radio-init', () => self.init());
    $(document).on('sw-radio-destroy', () => {
      $(document).off('sw-radio-init');
      $(document).off('change', '.sw-radios__input');
    })
  }
}
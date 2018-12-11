import partials from '../partials/*';

export default {
  init() {
    this.initPartials();
  },

  initPartials() {
    for (let i = partials.length - 1; 0 <= i; i--) {
      if (typeof partials[i].default === 'undefined') {
        continue;
      }

      if (typeof partials[i].default.prototype.init !== 'function') {
        continue;
      }

      let element;

      if (typeof partials[i].selector === 'string') {
        element = $(partials[i].selector);

        if (! element.length) {
          continue;
        }
      }

      if (element) {
        element.each((index, el) => {
          const instance = new partials[i].default();
          instance.element = $(el);

          element.data('instance', instance);

          partials[i].default.instances = partials[i].default.instances || [];
          partials[i].default.instances.push(instance);

          instance.init();
        });

      } else {
        const instance = new partials[i].default();

        partials[i].default.instances = partials[i].default.instances || [];
        partials[i].default.instances.push(instance);
        partials[i].default.instance = instance;

        instance.init();
      }
    }
  },

  finalize() {
    document.body.classList.add('is-loaded');
  },
};

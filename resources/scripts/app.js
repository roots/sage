import {domReady} from '@roots/sage/client';

/**
 * app.main
 */
const main = async (err) => {
  if (err) {
    // handle hmr errors
    console.error(err);
  }

  // application code
};

/**
 * Initialize
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 * @returns { void }
 */
if (module.hot) {
  window.addEventListener('load', (event) => {
    main();
    import.meta.webpackHot?.accept(main);
  });
} else {
  domReady(main);
}

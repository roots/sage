import domReady from '@wordpress/dom-ready';

domReady(() => {
  // DOM has been loaded
});

if (import.meta.hot) {
  import.meta.hot.on('vite:beforeUpdate', (payload) => {
    window.location.reload();
  });
}

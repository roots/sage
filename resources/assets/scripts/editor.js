import "@wordpress/edit-post";

const { domReady, blocks } = wp;

domReady(() => {
  blocks.unregisterBlockStyle("core/button", "outline");
  blocks.registerBlockStyle("core/button", {
    name: "outline",
    label: "Outline!!",
  });
});

/**
 * Accept module updates
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 */
module.hot &&
  module.hot.accept((err) => {
    console.err(err);
  });

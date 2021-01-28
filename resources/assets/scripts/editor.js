import "@wordpress/edit-post";
import domReady from "@wordpress/dom-ready";
import { unregisterBlockStyle, registerBlockStyle } from "@wordpress/blocks";

domReady(() => {
  unregisterBlockStyle("core/button", "outline");

  registerBlockStyle("core/button", {
    name: "outline",
    label: "Outline",
  });
});

/**
 * Accept module updates
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 */
module?.hot?.accept((err) => {
  console.err(err);
});

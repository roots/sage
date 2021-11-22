/**
 * This file allows you to add functionality to the Theme Customizer
 * live preview. jQuery is readily available.
 *
 * @see https://codex.wordpress.org/Theme_Customization_API
 */

/**
 * Change the blog name value.
 */
wp.customize('blogname', (value) => {
  value.bind((to) => $('.brand').text(to));
});

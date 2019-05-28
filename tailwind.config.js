// Tailwind plugins
const { wordpressUtilities } = require('tailwindcss-wordpress');

/*
 |--------------------------------------------------------------------------
 | Tailwind CSS – https://tailwindcss.com
 |--------------------------------------------------------------------------
 |
 | Tailwind CSS allows you to easily build your build your own CSS framework
 | according to your site’s needs. Use the configuration below to generate
 | utility classes from tokens in your design system.
 |
 | Combined with Blade, you’ll find you spend less time writing CSS and more
 | time designing consistent, responsive templates which use dynamic, reusable
 | components.
 |
 | Combined with Purgecss, you’ll find your production stylesheets are much
 | smaller since only the classes used in the templates are kept.
 |
 | By default, we are extending Tailwind’s base theme, including the container
 | core plugin, and using an external plugin for generating WordPress classes.
 |
 */

module.exports = {
  theme: {
    extend: {
      colors: {
        primary: '#525ddc',
      },
    },
    container: {
      center: true,
      padding: '1rem',
    },
  },
  variants: {},
  plugins: [
    wordpressUtilities,
  ],
};

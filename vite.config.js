import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import {
  wordpressPlugin,
  wordpressRollupPlugin,
  wordpressThemeJson,
} from './resources/js/build/wordpress';
import tailwindConfig from './tailwind.config.js';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/js/app.js',
        'resources/js/editor.js',
        'resources/styles/app.scss',
        'resources/styles/editor.scss',
      ],
      refresh: true,
    }),

    wordpressPlugin(),
    wordpressRollupPlugin(),

    wordpressThemeJson({
      tailwindConfig,
      disableTailwindColors: false,
      disableTailwindFonts: false,
      disableTailwindFontSizes: false,
    }),
  ],
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: `@use "settings/_variables.scss";`,
      },
    },
  },
});

import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import { extractWordPressDependencies, processThemeJson } from './resources/js/build/wordpress'
import tailwindConfig from './tailwind.config.js'

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/css/editor.css',
        'resources/js/editor.js',
      ],
      refresh: true,
    }),
    extractWordPressDependencies(),

    // Generate the theme.json file in the public/build/assets directory
    // based on the Tailwind config and the theme.json file from base theme folder
    processThemeJson({
      tailwindConfig,
      disableTailwindColors: false,
      disableTailwindFonts: false,
      disableTailwindFontSizes: false,
    }),
  ],
  base: '/app/themes/sage/public/build/',
})

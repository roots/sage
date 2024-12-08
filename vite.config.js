import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import {
  wordpressPlugin,
  wordpressRollupPlugin,
  wordPressThemeJson,
} from './resources/scripts/build/wordpress'
import tailwindConfig from './tailwind.config.js'

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/styles/app.css',
        'resources/scripts/app.js',
        'resources/styles/editor.css',
        'resources/scripts/editor.js',
      ],
      refresh: true,
    }),
    wordpressPlugin(),
    wordpressRollupPlugin(),
    wordPressThemeJson({
      tailwindConfig,
      settings: {
        background: {
          backgroundImage: true,
        },
        color: {
          custom: false,
          customDuotone: false,
          customGradient: false,
          defaultDuotone: false,
          defaultGradients: false,
          defaultPalette: false,
          duotone: [],
        },
        custom: {
          spacing: {},
          typography: {
            'font-size': {},
            'line-height': {},
          },
        },
        spacing: {
          padding: true,
          units: ['px', '%', 'em', 'rem', 'vw', 'vh'],
        },
        typography: {
          customFontSize: false,
        },
      },
      disableColors: false,
      disableFonts: false, 
      disableFontSizes: false,
    }),
  ],
})
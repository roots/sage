// @ts-check

/**
 * Build configuration
 *
 * @see {@link https://bud.js.org/guides/configure}
 * @param {import('@roots/bud').Bud} app
 */
export default async (app) => {
  app
    /**
     * Application entrypoints
     */
    .entry({
      app: ["@scripts/app", "@styles/app"],
      editor: ["@scripts/editor", "@styles/editor"],
    })

    /**
     * Directory contents to be included in the compilation
     */
    .assets(["images"])

    /**
     * Matched files trigger a page reload when modified
     */
    .watch(["resources/views/**/*", "app/**/*"])

    /**
     * Proxy origin (`WP_HOME`)
     */
    .proxy("http://rebeltheme.test")

    /**
     * Development origin
     */
    .serve("http://0.0.0.0:3000")

    /**
     * URI of the `public` directory
     */
    .setPublicPath("/app/themes/rig-sage/public/")

    /**
     * Generate WordPress `theme.json`
     *
     * @note This overwrites `theme.json` on every build.
     */
    .wpjson
    .settings({
      blocks: {
        'core/button': {
          border: {
            radius: false
          }
        }
      },
      color: {
        palette: [
          {
            slug: 'red',
            color: '#D11141',
            name: 'Red'
          },
          {
            slug: 'black',
            color: '#111111',
            name: 'Black'
          }
        ],
        custom: false,
        customGradient: false,
        defaultPalette: false,
        defaultGradients: false,
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
        fontSizes: [
          {
            name: 'Heading 1',
            size: '56px',
            slug: 'heading-1'
          },
          {
            name: 'Heading 2',
            size: '34px',
            slug: 'heading-2'
          },
          {
            name: 'Heading 3',
            size: '24px',
            slug: 'heading-3'
          },
          {
            name: 'Normal',
            size: '1rem',
            slug: 'normal-font-size'
          },
          {
            name: 'Primary Navigation',
            size: '1rem',
            slug: 'primary-nav-font-size'
          },
          {
            name: 'Utility Navigation',
            size: '0.75rem',
            slug: 'utility-nav-font-size'
          },
          {
            name: 'CTA',
            size: '0.875rem',
            slug: 'cta-font-size'
          }
        ],
        fontFamilies: [
          {
            fontFamily: 'Noto Sans',
            slug: 'noto-sans',
            name: 'Noto Sans'
          }
        ]
      },
    })
    .enable()

  app.sass
    .importGlobal([
      '@src/styles/config',
    ])

};

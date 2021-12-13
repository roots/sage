/**
 * @typedef {import('@roots/bud').Bud} Bud
 *
 * @param {Bud} config
 */

module.exports = async (config) =>
  config
    /**
     * Application entrypoints
     *
     * Paths are relative to your resources directory
     */
    .entry({
      app: ['scripts/app.js', 'styles/app.css'],
      editor: ['scripts/editor.js', 'styles/editor.css'],
      customizer: 'scripts/customizer.js',
    })

    /**
     * These files should be processed as part of the build
     * even if they are not explicitly imported in application assets.
     */
    .assets(['resources/images'])

    /**
     * These files will trigger a full page reload
     * when modified.
     */
    .watch([
      'tailwind.config.js',
      'resources/views/**/*.blade.php',
      'app/View/**/*.php',
    ])

    /**
     * Target URL to be proxied by the dev server.
     *
     * This is your local dev server.
     */
    .proxy('http://example.test');

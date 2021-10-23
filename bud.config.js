/**
 * Client assets build configuration
 *
 * @typedef {import('@roots/bud').Framework} Config
 *
 * @param {Config} config
 * @returns {Config}
 */
module.exports = (config) =>
  config
    /**
     * Use sage preset
     */
    .use(require('@roots/sage'))

    /**
     * Application entrypoints
     */
    .entry({
      app: '**/app.{js,css}',
      editor: '**/editor.{js,css}',
      customizer: '**/customizer.{js,css}',
    })

    /**
     * These files should be processed as part of the build
     * even if they are not explicitly imported in application assets.
     */
    .assets(['assets/images'])

    /**
     * These files will trigger a full page reload
     * when modified.
     */
    .watch([
      'tailwind.config.js',
      'resources/views/*.blade.php',
      'app/View/**/*.php',
    ]);

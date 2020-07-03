/**
 * Bud - asset compilation framework.
 *
 * @const {Object.<Bud>} bud
 */

const bud = require('@roots/budpack');

/**
 * Set source assets directory.
 *
 * @param {string} directory - project source dir.
 */

bud.srcPath('resources/assets');

/**
 * Set compiled assets directory.
 *
 * @param {string} directory - project dist dir.
 */

bud.distPath('dist');

/**
 * Resolve modules through webpack aliases. Useful for situations
 * that may otherwise require brittle relative paths.
 *
 * @example
 *  `import {myScript} from '../../../components/myScript'`
 *  `import {myScript} from '@scripts/components/myScript'`
 *
 * @param {Object.<string, array>}
 */

bud.alias({
  '@fonts':   bud.src('fonts'),
  '@images':  bud.src('images'),
  '@scripts': bud.src('scripts'),
  '@styles':  bud.src('styles'),
});

/**
 * Automatically load modules instead of needing to explicitly import them.
 *
 * @param {Object.<string, array>}
 */

bud.auto({
  jquery: ['$', 'window.jQuery'],
});

/**
 * Configure live reloading.
 *
 * @property {string}  [host='localhost']
 * @property {number}  [port=3000]
 * @property {boolean} [enabled=!bud.inProduction]
 * @property {string}  [proxy='']
 */

bud.sync({
  proxy: 'http://bud-sandbox.valet',
});

/**
 * Enable or disable filename hashing on compiled assets.
 *
 * @param {boolean} enabled - filename hashing enabled.
 */

bud.hash(false);

/**
 * WordPress dependency manifest
 *
 * @todo splitChunks breaks this
 */
bud.wpManifest(false);

/**
 * Compile application JavaScript.
 *
 * @param  {string} name    - output name.
 * @param  {array}  entries - assets to include in the bundle.
 */

bud
  .bundle('scripts/editor', ['scripts/editor.js'])
  .bundle('scripts/app', ['scripts/app.js'])
  .bundle('scripts/customizer', ['scripts/customizer.js'])

/**
 * Compile application SCSS.
 *
 * @param  {string} name    - output name.
 * @param  {array}  entries - assets to include in the bundle.
 */

bud
  .bundle('styles/editor', ['styles/editor.scss'])
  .bundle('styles/app', ['styles/app.scss'])

/**
 * Copy static assets.
 *
 * @param {string} src  - copy from src relative dir
 * @param {string} dist - copy to dist relative dir
 */

bud
  .copyAll('images', 'images')
  .copyAll('fonts', 'fonts');

/**
 * Configure Babel and PostCSS.
 *
 * You may utilize a standard babel.config.js and/or postcss.config.js
 * file located in the project root, either alongside or in lieue of
 * these configurations.
 *
 * Conflicts between config sources will be resolved in favor of
 * the ones located in this file.
 *
 * @see https://babeljs.io/docs/en/configuration
 * @see https://github.com/postcss/postcss#options
 */

bud
  .babel(bud.preset('babel/preset-wp'))
  .postcss(bud.preset('postcss'));

/**
 * Translate strings from JS source assets.
 *
 * If you are already using `yarn translate` then there is no
 * reason to run this in addition to that. You may remove it.
 *
 * @param {string} potfile - project relative path to translation file
 */

bud.translate('resources/languages/sage.pot');

/**
 * Purge unused CSS from production builds.
 *
 * @see https://purgecss.com/guides/wordpress.html
 * @see https://purgecss.com/configuration.html
 */

bud.inProduction && bud.purge({
  content: [bud.project('resources/views/**/*.blade.php')],
  allow: require('purgecss-with-wordpress').whitelist,
  allowPatterns: require('purgecss-with-wordpress').whitelistPatterns,
});

/**
 * Export finalized configuration.
 * @exports {Object.<Bud>}
 */

module.exports = bud;

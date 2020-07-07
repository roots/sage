/**
 * Build theme assets.
 */

const bud = require('@roots/budpack');

/**
 * Set source directory.
 */

bud.srcPath('resources/assets');

/**
 * Set webpack aliases.
 */

bud.alias({
  '@fonts':   bud.src('fonts'),
  '@images':  bud.src('images'),
  '@scripts': bud.src('scripts'),
  '@styles':  bud.src('styles'),
});

/**
 * Autoload modules.
 */

bud.auto({
  jquery: ['$', 'window.jQuery'],
});

/**
 * Configure live reload.
 */

bud.sync({
  enabled: !bud.inProduction,
  proxy: 'http://bud-sandbox.valet',
});

/**
 * Filename hashing.
 */

bud.hash(false);

/**
 * Generate a WordPress dependency manifest.
 * @see  @wordpress/dependency-manifest-webpack-plugin
 * @todo splitChunks breaks this
 */

bud.dependencyManifest()

/**
 * Optimize performance by inlining code split from bundles.
 */

bud.inlineManifest({
  enabled: true,
  name: 'runtime',
});

/**
 * Compile application JavaScript.
 */

bud
  .bundle('scripts/editor', [bud.src('scripts/editor.js')])
  .bundle('scripts/app', [bud.src('scripts/app.js')])
  .bundle('scripts/customizer', [bud.src('scripts/customizer.js')])

/**
 * Compile application SCSS.
 */

bud
  .bundle('styles/editor', [bud.src('styles/editor.scss')])
  .bundle('styles/app', [bud.src('styles/app.scss')])

/**
 * Copy static assets.
 */

bud
  .copyAll(bud.src('images'), bud.dist('images'))
  .copyAll(bud.src('fonts'), bud.dist('fonts'));

/**
 * Configure Babel and PostCSS.
 */

bud
  .babel(bud.preset('babel/preset-wp'))
  .postCss(bud.preset('postcss'));

/**
 * Translate strings from JS source assets.
 */

bud.translate('resources/languages/sage.pot');

/**
 * Purge unused CSS from production builds.
 */

bud.purge({
  enabled: bud.inProduction,
  content: [bud.project('resources/views/**/*.blade.php')],
  allow: require('purgecss-with-wordpress').whitelist,
  allowPatterns: require('purgecss-with-wordpress').whitelistPatterns,
});

/**
 * Export finalized configuration.
 */

module.exports = bud;

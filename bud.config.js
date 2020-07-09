/**
 * Bud: asset management framework.
 * @see https://roots.github.io/bud-support
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
});

/**
 * Filename hashing.
 */
bud.hash(false);

/**
 * Compile application scripts.
 */
bud
  .bundle('scripts/editor', [bud.src('scripts/editor.js')])
  .bundle('scripts/app', [bud.src('scripts/app.js')])
  .bundle('scripts/customizer', [bud.src('scripts/customizer.js')])

/**
 * Compile application styles.
 */
bud
  .bundle('styles/editor', [bud.src('styles/editor.scss')])
  .bundle('styles/app', [bud.src('styles/app.scss')])

/**
 * Extract dependencies.
 */
bud.vendor('scripts/vendor');

/**
 * Inline runtime manifest.
 */
bud.inlineManifest('scripts/manifest');

/**
 * Generate a WordPress dependency manifest.
 * @todo splitChunks breaks this
 */
bud.dependencyManifest()

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
bud.translate(
  bud.project('resources/languages/sage.pot')
);

/**
 * Purge unused CSS from bundles.
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

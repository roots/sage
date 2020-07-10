/**
 * Bud: asset management framework.
 * @see https://roots.github.io/bud-support
 */
const bud = require('@roots/budpack');

/**
 * Set source directory.
 */
bud
  .srcPath('resources/assets')
  .distPath('dist');

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
 * Autoload common modules.
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
 * Compile application assets.
 */
bud
  .bundle('app', [
    bud.src('scripts/app.js'),
    bud.src('styles/app.scss'),
  ])
  .bundle('editor', [
    bud.src('scripts/editor.js'),
    bud.src('styles/editor.scss'),
  ])
  .bundle('customizer', [
    bud.src('scripts/customizer.js'),
  ])

/**
 * Group vendored scripts, generate manifests and version assets.
 */
bud
  .dependencyManifest()
  .inlineManifest()
  .vendor()
  .hash();

/**
 * Copy static assets.
 */
bud
  .copyAll(bud.src('images'), bud.dist('images'))
  .copyAll(bud.src('fonts'), bud.dist('fonts'));

/**
 * Configure transpilers.
 */
bud
  .babel(bud.preset('babel/preset-wp'))
  .postCss(bud.preset('postcss'));

/**
 * Purge unused application styles.
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

const bud = require('@roots/bud')

/**
 * Extensions
 */
bud
  .extend([
    require('@roots/bud-sass'),
    require('@roots/bud-eslint').plugin,
    require('@roots/bud-stylelint').plugin,
    require('@roots/bud-purgecss').plugin,
    require('@roots/bud-wordpress-manifests'),
  ])

/**
 * Project paths.
 */
bud
  .srcPath('resources/assets')
  .publicPath(bud.env.get('APP_PUBLIC_PATH'))

/**
 * Application assets
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
  .copy('{images,fonts}/**/*')

/**
 * Applied to all builds.
 */
bud
  .provide({jquery: ['$', 'jQuery']})
  .vendor()
  .runtimeManifest()

/**
 * Production builds.
 */
if (bud.mode.is('production')) {
  bud
    .gzip()
    .hash()
    .mini()
    .devtool('hidden-source-map')
    .purgecss(require('@roots/bud-purgecss').preset)
}

/**
 * Development builds.
 */
if (bud.mode.is('development')) {
  bud.dev({
    host: bud.env.get('APP_HOST'),
  })
  .devtool('inline-cheap-module-source-map')
}

/**
 * Compile build.
 */
bud.compile()

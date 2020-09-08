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
  .distPath('dist')
  .srcPath('resources/assets')
  .publicPath('app/themes/sage/dist')

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

/**
 * Applied to all builds.
 */
bud
  .provide({jquery: ['$', 'jQuery']})
  .vendor()
  .runtimeManifest()

/**
 * Applied to development builds.
 */
bud.when(bud.inDevelopment, () =>
  bud.dev({
    host: bud.env.get('APP_DEV_HOST'),
    port: bud.env.get('APP_DEV_PORT'),
  })
  .devtool('inline-cheap-module-source-map')
)

/**
 * Applied to production builds.
 */
bud.when(bud.inProduction, () =>
  bud
    .hash()
    .devtool('hidden-source-map')
    .mini()
    .purgecss(
      require('@roots/bud-purgecss').preset
    )
)

/**
 * Compile build.
 */
bud.compile()

// Alternatiely, export to webpack:
// module.exports = bud.config(bud)

/** Import config first, since it sets NODE_ENV, which is used by laravel-mix */
const config = require('./config');

const path = require('path');
const url = require('url');
const rimraf = require('rimraf');
const mix = require('laravel-mix');

/** Enable injection over SSL */
if (url.parse(config.devUrl).protocol === 'https:') {
  process.env.NODE_TLS_REJECT_UNAUTHORIZED = 0;
}

/** Nuke dist folder */
rimraf.sync(config.paths.dist);

/** Inform mix of Sage's directory output structure */
module.exports = mix
  .setPublicPath(path.relative(config.paths.root, config.paths.dist))
  .setResourceRoot(config.publicPath)
  .webpackConfig({
    module: {
      rules: [
        {
          enforce: 'pre',
          test: /\.(js|s?[ca]ss)$/,
          include: config.paths.assets,
          loader: 'import-glob',
        },
      ],
    },
    externals: {
      jquery: 'jQuery',
    },
  })
  .autoload({
    jquery: ['$', 'window.jQuery'],
  });

/** BrowserSync */
if (config.enabled.watcher) {
  mix.browserSync({
    open: config.open,
    proxy: config.devUrl,
    files: [`${config.paths.dist}/**/*`],
    reloadDelay: 250,
    plugins: [
      {
        module: 'bs-html-injector',
        options: {
          files: config.html,
        },
      },
    ],
  });
}

/** Uglify + Image optimization */
if (config.enabled.optimize) {
  mix.options({
    postCss: [require('cssnano')],
  });
}

/** Cache-busting */
if (config.enabled.cacheBusting) {
  mix.version();
}

/** Remove leading slashes in mix-manifest.json */
mix.then(() => {
  const manifest = File.find(`${config.paths.dist}/mix-manifest.json`);
  const json = JSON.parse(manifest.read());
  Object.keys(json).forEach(key => {
    const hashed = json[key];
    delete json[key];
    json[key.replace(/^\/+/g, '')] = hashed.replace(/^\/+/g, '');
  });
  manifest.write(JSON.stringify(json, null, 2));
});

require('@roots/bud').pipe([
  ({use}) => use([
    '@roots/bud-react',
    '@roots/bud-postcss',
    '@roots/bud-sass',
    '@roots/bud-eslint',
    '@roots/bud-purgecss',
    '@roots/bud-stylelint',
    '@roots/bud-wordpress-manifests',
  ]),

  ({env, srcPath, publicPath, provide, alias}) => {
    srcPath('resources/assets');

    publicPath(env.get('APP_PUBLIC_PATH'));

    provide({jquery: ['$', 'jQuery'] });

    alias({
      '@scripts': './scripts',
      '@styles': './styles',
      '@fonts': './fonts',
      '@images': './images',
    });
  },

  ({copy, entry}) => {
    entry('app', [
      '@styles/app.scss',
      '@scripts/app.js',
    ]);

    entry('editor', [
      '@scripts/editor.js',
      '@styles/editor.scss',
    ]);

    entry('customizer', [
      '@scripts/customizer.js',
    ]);

    copy('images/*');
  },

  ({mode, when}) => {
    when(mode.is('production'), bud => {
      bud.minify();
      bud.hash();
      bud.vendor();
      bud.runtime();
      bud.devtool('hidden-source-map');
      bud.purge(bud.presets.get('purgecss.wp'));
    })

    when(mode.is('development'), ({dev, env}) =>
      dev({host: env.get('APP_HOST')})
    );
  },
]).run();

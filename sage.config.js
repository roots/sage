const {bud} = require('@roots/bud')

bud.use([
  '@roots/bud-react',
  '@roots/bud-postcss',
  '@roots/bud-sass',
  '@roots/bud-eslint',
  '@roots/bud-purgecss',
  '@roots/bud-stylelint',
  '@roots/bud-wordpress-manifests',
]);

bud.srcPath('resources/assets');
bud.publicPath(bud.env.get('APP_PUBLIC_PATH'));
bud.buildCache(bud.project('storage/bud/records.json'))

bud.alias({
  '@scripts': bud.src('scripts'),
  '@styles': bud.src('styles'),
  '@fonts': bud.src('fonts'),
  '@images': bud.src('images'),
});

bud.provide({jquery: ['$', 'jQuery']});

bud.entry('app', ['@styles/app.scss', '@scripts/app.js']);
bud.entry('editor', ['@scripts/editor.js', '@styles/editor.scss']);
bud.entry('customizer', ['@scripts/customizer.js']);

bud.copy('images/*');

bud.when(bud.mode.is('production'), bud => {
  bud.minify();
  bud.hash();
  bud.imagemin();
  bud.vendor();
  bud.runtime();
  bud.devtool('hidden-source-map');
  bud.purge(bud.presets.get('purgecss.wp'));
})

bud.when(bud.mode.is('development'), bud =>
  bud.dev({host: bud.env.get('APP_HOST')})
);

bud.run();

module.exports = {
  browsersync: {
  proxy: 'https://example.test',
    files: [
      '(app|config|resources)/**/*.php',
      'dist/(styles|scripts)/**/*.(css|js)',
    ],
  },
  entry: {
    root: 'resources/assets',
    styles: [
      'styles/app.scss',
    ],
    scripts: [
      'scripts/app.js',
      'scripts/customizer.js',
    ],
    dirs: [
      'images',
      'fonts',
    ],
  },
  autoload: {
    jQuery: true,
  },
}

// @ts-check
const {sage} = require('@roots/sage');

sage.when(sage.isProduction, () => {
  sage.purge({
    content: [
      'resources/views/**/*',
      'resources/assets/scripts/**/*',
    ],
    css: ['resources/assets/styles/**/*'],
  });
});

sage
  .entry('app', ['styles/app.scss', 'scripts/app.js'])
  .entry('editor', ['styles/editor.scss', 'scripts/editor.js'])
  .run();

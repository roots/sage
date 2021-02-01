// @ts-check
const Sage = require('@roots/sage');

/**
 * Sage theme
 */
const theme = Sage.bootstrap();

theme
  .entry('app', ['styles/app.scss', 'scripts/app.js'])
  .entry('editor', ['styles/editor.scss', 'scripts/editor.js'])
  .entry('customizer', ['scripts/customizer.js'])
  .run();

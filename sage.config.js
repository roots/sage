// @ts-check
const Sage = require('@roots/sage');

Sage.bootstrap()
  .entry('app', ['@styles/app.scss', '@scripts/app.js'])
  .entry('editor', ['@styles/editor.scss', '@scripts/editor.js'])
  .entry('customizer', ['@scripts/customizer.js'])
  .run();

// @ts-check
const {sage} = require('@roots/sage');

sage
  .entry({
    app: ['**/app.{(t|j)s(x)?,vue,(s)?css}'],
    editor: ['**/editor.{(t|j)s(x)?,vue,(s)?css}'],
    customizer: ['scripts/customizer.js'],
  })
  .copy({'assets/': 'resources/{images,fonts}/**/*'})
  .run();

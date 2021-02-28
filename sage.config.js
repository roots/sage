// @ts-check
const {sage} = require('@roots/sage');

sage
  .entry({
    app: ['**/app.{(j|t)s(x)?,(s)?css}'],
    editor: ['**/editor.{(j|t)s(x)?,(s)?css}'],
    customizer: ['scripts/customizer.(j|t)s'],
  })
  .copy({'assets/': 'resources/{images,fonts}/**/*'})
  .run();

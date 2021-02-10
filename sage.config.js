// @ts-check
const Sage = require('@roots/sage');

Sage.bootstrap()

  .globs({
    app: '**/app.{(t|j)s(x)?,(s)?css}',
    editor: '**/editor.{(t|j)s(x)?,(s)?css}',
    customizer: '**/customizer.{(t|j)s(x)?,(s)?css}',
  })

  .copy({
    images: 'resources/**/*.{png,gif,jpg,jp(e)?g,webp,svg}',
    fonts: 'resources/**/*.{otf,ttf,woff(2)?,eot}',
  })

  .run();

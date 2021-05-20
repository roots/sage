import {Framework} from '@roots/bud';
import * as sage from '@roots/sage';

export default (app: Framework) =>
  app
    .use(sage)
    .entry({
      app: ['**/app.{js,css}'],
      editor: ['**/editor.{js,css}'],
      customizer: ['**/customizer.{js,css}'],
    })
    .assets(['assets/images'])
    .persist();

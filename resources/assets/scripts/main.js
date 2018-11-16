// import external dependencies
import 'jquery';

// Import everything from autoload
import './autoload/**/*';

// import local dependencies
import Router from './util/Router';
import common from './routes/common';
import home from './routes/home';

/**
 * Import  the `about` route only when <body> contains 'about-us' class
 * using dynamic import technique
 *
 * NOTE: if your build system supports `import()` function
 * then you could use this construction instead:
 * const aboutUs = () => import('./routes/about');
 */

const aboutUs = () =>  new Promise((resolve) => require.ensure('./routes/about', (require) => {
    const about = require('./routes/about');
    resolve(about);
  })
);

/** Populate Router instance with DOM routes */
const routes = new Router({
  // All pages
  common,
  // Home page
  home,
  // About Us page, note the change from about-us to aboutUs.
  aboutUs,
});

// Load Events
jQuery(document).ready(() => routes.loadEvents());

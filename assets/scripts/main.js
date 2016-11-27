// import external dependencies
import 'jquery';
import 'picturefill';
import 'foundation-sites/dist/js/foundation';
import 'motion-ui/dist/motion-ui';

// import local dependencies
import Router from './util/router';
import common from './routes/Common';
import home from './routes/Home';
import aboutUs from './routes/About';

// Use this variable to set up the common and page specific functions. If you
// rename this variable, you will also need to rename the namespace below.
const routes = {
  // All pages
  common,
  // Home page
  home,
  // About us page, note the change from about-us to aboutUs.
  aboutUs,
};

// Ensure correct images are set before plugins such as orbit begins measuring
// dimensions.
picturefill();
jQuery(document).foundation();
// Load Events
jQuery(document).ready(() => new Router(routes).loadEvents());

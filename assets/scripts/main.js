import $ from 'jquery';
import Router from './util/router';
import Common from './routes/Common';
import Home from './routes/Home';
import About from './routes/About';

// Import Bootstrap
import 'bootstrap/dist/js/umd/util.js';
import 'bootstrap/dist/js/umd/alert.js';
import 'bootstrap/dist/js/umd/button.js';
import 'bootstrap/dist/js/umd/carousel.js';
import 'bootstrap/dist/js/umd/collapse.js';
import 'bootstrap/dist/js/umd/dropdown.js';
import 'bootstrap/dist/js/umd/modal.js';
import 'bootstrap/dist/js/umd/scrollspy.js';
import 'bootstrap/dist/js/umd/tab.js';
import 'bootstrap/dist/js/umd/tooltip.js';
import 'bootstrap/dist/js/umd/popover.js';

// Use this variable to set up the common and page specific functions. If you
// rename this variable, you will also need to rename the namespace below.
const routes = {
  // All pages
  'common': Common,
  // Home page
  'home': Home,
  // About us page, note the change from about-us to about_us.
  'about_us': About
};

// Load Events
$(document).ready(() => new Router(routes).loadEvents());

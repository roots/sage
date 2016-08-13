// import external dependencies
import 'jquery'
import 'bootstrap/dist/js/bootstrap'

// import local dependencies
import Router from './util/router';
import common from './routes/Common';
import home from './routes/Home';
import about_us from './routes/About';

// Use this variable to set up the common and page specific functions. If you
// rename this variable, you will also need to rename the namespace below.
const routes = {
  // All pages
  common,
  // Home page
  home,
  // About us page, note the change from about-us to about_us.
  about_us
};

// Load Events
$(document).ready(() => new Router(routes).loadEvents());

// import external dependencies
import 'jquery';
import 'bootstrap/dist/js/bootstrap';

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

// Load Events
$(document).ready(() => new Router(routes).loadEvents());

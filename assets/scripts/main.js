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
// Ensures that the callback will always run
if (document.readyState === 'complete' || (document.readyState !== 'loading' && !document.documentElement.doScroll)) {
  new Router(routes).loadEvents();
} else {
  document.addEventListener('DOMContentLoaded', () => new Router(routes).loadEvents());
}

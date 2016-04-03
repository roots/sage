import $ from 'jquery';
import Router from './util/router';

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
var Sage = {
  // All pages
  'common': {
    init: function() {
      // JavaScript to be fired on all pages
    },
    finalize: function() {
      // JavaScript to be fired on all pages, after page specific JS is fired
    }
  },
  // Home page
  'home': {
    init: function() {
      // JavaScript to be fired on the home page
    },
    finalize: function() {
      // JavaScript to be fired on the home page, after the init JS
    }
  },
  // About us page, note the change from about-us to about_us.
  'about_us': {
    init: function() {
      // JavaScript to be fired on the about us page
    }
  }
};

// Load Events
$(document).ready(function() {
  new Router(Sage).loadEvents();
});

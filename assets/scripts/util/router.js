import $ from 'jquery';

/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 * ======================================================================== */

// The routing fires all common scripts, followed by the page specific scripts.
// Add additional events for more control over timing e.g. a finalize event
export default class Router {
  constructor(routes) {
    this.routes = routes;
  }

  fire(func, funcname, args) {
    funcname = (funcname === undefined) ? 'init' : funcname;
    let fire = func !== '';
    fire = fire && this.routes[func];
    fire = fire && typeof this.routes[func][funcname] === 'function';

    if (fire) {
      this.routes[func][funcname](args);
    }
  }

  loadEvents() {
    // Fire common init JS
    this.fire('common');

    // Fire page-specific init JS, and then finalize JS
    $.each(
      document.body.className.replace(/-/g, '_').split(/\s+/),
      (i, className) => {
        this.fire(className);
        this.fire(className, 'finalize');
      }
    );

    // Fire common finalize JS
    this.fire('common', 'finalize');
  }
}

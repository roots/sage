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

  fire(route, fn = 'init', args) {
    const fire = route !== '' && this.routes[route] && typeof this.routes[route][fn] === 'function';
    if (fire) {
      this.routes[route][fn](args);
    }
  }

  loadEvents() {
    // Fire common init JS
    this.fire('common');

    // Fire page-specific init JS, and then finalize JS
    document.body.className.replace(/-/g, '_').split(/\s+/).forEach((className) => {
      this.fire(className);
      this.fire(className, 'finalize');
    });

    // Fire common finalize JS
    this.fire('common', 'finalize');
  }
}

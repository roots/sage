/**
 * External Dependencies
 */
import 'jquery';
import 'bootstrap';

/**
 * Local Dependencies
 */
import Router from './util/Router';
import common from './routes/common';
import aboutUs from './routes/about';

/**
 * Helper function for document readiness
 */
function ready(fn) {
  if (document.readyState !== 'loading') return fn();
  document.addEventListener('DOMContentLoaded', fn);
}

/**
 * Populate the Router instance with DOM routes.
 *
 * common – Fired on all pages.
 * aboutUs – Fired on the About Us page, note the change from about-us to aboutUs (camelCase).
 */
const routes = new Router({
  common,
  aboutUs,
});

/**
 * Load Events
 */
ready(() => routes.loadEvents());

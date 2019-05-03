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
jQuery(document).ready(() => routes.loadEvents());

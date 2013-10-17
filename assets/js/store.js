// Copyright 2012 Google Inc.

/**
 * @author Chris Broadfoot (Google)
 * @fileoverview
 * Store model class for Store Locator library.
 */

/**
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Represents a store.
 * @example <pre>
 * var latLng = new google.maps.LatLng(40.7585, -73.9861);
 * var store = new storeLocator.Store('times_square', latLng, null);
 * </pre>
 * <pre>
 * var features = new storeLocator.FeatureSet(
 *     view.getFeatureById('24hour'),
 *     view.getFeatureById('express'),
 *     view.getFeatureById('wheelchair_access'));
 *
 * var store = new storeLocator.Store('times_square', latLng, features, {
 *   title: 'Times Square',
 *   address: '1 Times Square&lt;br>Manhattan, NY 10036'
 * });
 * </pre>
 * <pre>
 * store.distanceTo(map.getCenter());
 *
 * // override default info window
 * store.getInfoWindowContent = function() {
 *   var details = this.getDetails();
 *   return '&lt;h1>' + details.title + '&lt;h1>' + details.address;
 * };
 * </pre>
 * @param {string} id globally unique id of the store - should be suitable to
 * use as a HTML id.
 * @param {!google.maps.LatLng} location location of the store.
 * @param {storeLocator.FeatureSet} features the features of this store.
 * @param {Object.<string, *>=} props any additional properties.
 * <p> Recommended fields are:
 *      'title', 'address', 'phone', 'misc', 'web'. </p>
 * @constructor
 * @implements storeLocator_Store
 */
storeLocator.Store = function(id, location, features, props) {
  this.id_ = id;
  this.location_ = location;
  this.features_ = features || storeLocator.FeatureSet.NONE;
  this.props_ = props || {};
};
storeLocator['Store'] = storeLocator.Store;

/**
 * Sets this store's Marker.
 * @param {google.maps.Marker} marker the marker to set on this store.
 */
storeLocator.Store.prototype.setMarker = function(marker) {
  this.marker_ = marker;
  google.maps.event.trigger(this, 'marker_changed', marker);
};

/**
 * Gets this store's Marker
 * @return {google.maps.Marker} the store's marker.
 */
storeLocator.Store.prototype.getMarker = function() {
  return this.marker_;
};

/**
 * Gets this store's ID.
 * @return {string} this store's ID.
 */
storeLocator.Store.prototype.getId = function() {
  return this.id_;
};

/**
 * Gets this store's location.
 * @return {google.maps.LatLng} this store's location.
 */
storeLocator.Store.prototype.getLocation = function() {
  return this.location_;
};

/**
 * Gets this store's features.
 * @return {storeLocator.FeatureSet} this store's features.
 */
storeLocator.Store.prototype.getFeatures = function() {
  return this.features_;
};

/**
 * Checks whether this store has a particular feature.
 * @param {!storeLocator.Feature} feature the feature to check for.
 * @return {boolean} true if the store has the feature, false otherwise.
 */
storeLocator.Store.prototype.hasFeature = function(feature) {
  return this.features_.contains(feature);
};

/**
 * Checks whether this store has all the given features.
 * @param {storeLocator.FeatureSet} features the features to check for.
 * @return {boolean} true if the store has all features, false otherwise.
 */
storeLocator.Store.prototype.hasAllFeatures = function(features) {
  if (!features) {
    return true;
  }
  var featureList = features.asList();
  for (var i = 0, ii = featureList.length; i < ii; i++) {
    if (!this.hasFeature(featureList[i])) {
      return false;
    }
  }
  return true;
};

/**
 * Gets additional details about this store.
 * @return {Object} additional properties of this store.
 */
storeLocator.Store.prototype.getDetails = function() {
  return this.props_;
};

/**
 * Generates HTML for additional details about this store.
 * @private
 * @param {Array.<string>} fields the optional fields of this store to output.
 * @return {string} html version of additional fields of this store.
 */
storeLocator.Store.prototype.generateFieldsHTML_ = function(fields) {
  var html = [];
  for (var i = 0, ii = fields.length; i < ii; i++) {
    var prop = fields[i];
    if (this.props_[prop]) {
      html.push('<div class="');
      html.push(prop);
      html.push('">');
      html.push(this.props_[prop]);
      html.push('</div>');
    }
  }
  return html.join('');
};

/**
 * Generates a HTML list of this store's features.
 * @private
 * @return {string} html list of this store's features.
 */
storeLocator.Store.prototype.generateFeaturesHTML_ = function() {
  var html = [];
  html.push('<ul class="features">');
  var featureList = this.features_.asList();
  for (var i = 0, feature; feature = featureList[i]; i++) {
    html.push('<li>');
    html.push(feature.getDisplayName());
    html.push('</li>');
  }
  html.push('</ul>');
  return html.join('');
};

/**
 * Gets the HTML content for this Store, suitable for use in an InfoWindow.
 * @return {string} a HTML version of this store.
 */
storeLocator.Store.prototype.getInfoWindowContent = function() {
  if (!this.content_) {
    // TODO(cbro): make this a setting?
    var fields = ['title', 'address', 'phone', 'misc', 'web'];
    var html = ['<div class="store">'];
    html.push(this.generateFieldsHTML_(fields));
    html.push(this.generateFeaturesHTML_());
    html.push('</div>');

    this.content_ = html.join('');
  }
  return this.content_;
};

/**
 * Gets the HTML content for this Store, suitable for use in suitable for use
 * in the sidebar info panel.
 * @this storeLocator.Store
 * @return {string} a HTML version of this store.
 */
storeLocator.Store.prototype.getInfoPanelContent = function() {
  return this.getInfoWindowContent();
};

/**
 * Keep a cache of InfoPanel items (DOM Node), keyed by the store ID.
 * @private
 * @type {Object}
 */
storeLocator.Store.infoPanelCache_ = {};

/**
 * Gets a HTML element suitable for use in the InfoPanel.
 * @return {Node} a HTML element.
 */
storeLocator.Store.prototype.getInfoPanelItem = function() {
  var cache = storeLocator.Store.infoPanelCache_;
  var store = this;
  var key = store.getId();
  if (!cache[key]) {
    var content = store.getInfoPanelContent();
    cache[key] = $('<li class="store" id="store-' + store.getId() +
        '">' + content + '</li>')[0];
  }
  return cache[key];
};

/**
 * Gets the distance between this Store and a certain location.
 * @param {google.maps.LatLng} point the point to calculate distance to/from.
 * @return {number} the distance from this store to a given point.
 * @license
 *  Latitude/longitude spherical geodesy formulae & scripts
 *  (c) Chris Veness 2002-2010
 *  www.movable-type.co.uk/scripts/latlong.html
 */
storeLocator.Store.prototype.distanceTo = function(point) {
  var R = 6371; // mean radius of earth
  var location = this.getLocation();
  var lat1 = storeLocator.toRad_(location.lat());
  var lon1 = storeLocator.toRad_(location.lng());
  var lat2 = storeLocator.toRad_(point.lat());
  var lon2 = storeLocator.toRad_(point.lng());
  var dLat = lat2 - lat1;
  var dLon = lon2 - lon1;

  var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
          Math.cos(lat1) * Math.cos(lat2) *
          Math.sin(dLon / 2) * Math.sin(dLon / 2);
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return R * c;
};

/**
 * Fired when the <code>Store</code>'s <code>marker</code> property changes.
 * @name storeLocator.Store#event:marker_changed
 * @param {google.maps.Marker} marker
 * @event
 */

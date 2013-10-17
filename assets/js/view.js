// Copyright 2012 Google Inc.

/**
 * @author Chris Broadfoot (Google)
 * @fileoverview
 * This library makes it easy to create a fully-featured Store Locator for
 * your business's website.
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
 * Data feed that returns stores based on a given bounds and a set of features.
 * @example <pre>
 * // always returns the same stores
 * function SimpleStaticFeed(stores) {
 *   this.stores = stores;
 * }
 * SimpleStaticFeed.prototype.getStores = function(bounds, features, callback) {
 *   callback(this.stores);
 * };
 * new storeLocator.View(map, new SimpleStaticFeed());
 * </pre>
 * @interface
 */
storeLocator.DataFeed = function() {};
storeLocator['DataFeed'] = storeLocator.DataFeed;

/**
 * Fetch stores, based on bounds to search within, and features to filter on.
 * @param {google.maps.LatLngBounds} bounds the bounds to search within.
 * @param {storeLocator.FeatureSet} features the features to filter on.
 * @param {function(Array.<!storeLocator.Store>)} callback the callback
 * function.
 */
storeLocator.DataFeed.prototype.getStores =
    function(bounds, features, callback) {};

/**
 * The main store locator object.
 * @example <pre>
 * new storeLocator.View(map, dataFeed);
 * </pre>
 * <pre>
 * var features = new storeLocator.FeatureSet(feature1, feature2, feature3);
 * new storeLocator.View(map, dataFeed, {
 *   markerIcon: 'icon.png',
 *   features: features,
 *   geolocation: false
 * });
 * </pre>
 * <pre>
 * // refresh stores every 10 seconds, regardless of interaction on the map.
 * var view = new storeLocator.View(map, dataFeed, {
 *   updateOnPan: false
 * });
 * setTimeout(function() {
 *   view.refreshView();
 * }, 10000);
 * </pre>
 * <pre>
 * // custom MarkerOptions, by overriding the createMarker method.
 * view.createMarker = function(store) {
 *   return new google.maps.Marker({
 *     position: store.getLocation(),
 *     icon: store.getDetails().icon,
 *     title: store.getDetails().title
 *   });
 * };
 * </pre>
 * @extends {google.maps.MVCObject}
 * @param {google.maps.Map} map the map to operate upon.
 * @param {storeLocator.DataFeed} data the data feed to fetch stores from.
 * @param {storeLocator.ViewOptions} opt_options
 * @constructor
 * @implements storeLocator_View
 */
storeLocator.View = function(map, data, opt_options) {
  this.map_ = map;
  this.data_ = data;
  this.settings_ = $.extend({
      'updateOnPan': true,
      'geolocation': true,
      'features': new storeLocator.FeatureSet
    }, opt_options);

  this.init_();
  google.maps.event.trigger(this, 'load');
  this.set('featureFilter', new storeLocator.FeatureSet);
};
storeLocator['View'] = storeLocator.View;

storeLocator.View.prototype = new google.maps.MVCObject;

/**
 * Attempt to perform geolocation and pan to the given location
 * @private
 */
storeLocator.View.prototype.geolocate_ = function() {
  var that = this;
  if (window.navigator && navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(pos) {
      var loc = new google.maps.LatLng(
        pos.coords.latitude, pos.coords.longitude);

      that.getMap().setCenter(loc);
      that.getMap().setZoom(11);
      google.maps.event.trigger(that, 'load');
    }, undefined, /** @type GeolocationPositionOptions */({
      maximumAge: 60 * 1000,
      timeout: 10 * 1000
    }));
  }
};

/**
 * Initialise the View object
 * @private
 */
storeLocator.View.prototype.init_ = function() {
  if (this.settings_['geolocation']) {
    this.geolocate_();
  }
  this.markerCache_ = {};
  this.infoWindow_ = new google.maps.InfoWindow;

  var that = this;
  var map = this.getMap();

  this.set('updateOnPan', this.settings_['updateOnPan']);

  google.maps.event.addListener(this.infoWindow_, 'closeclick', function() {
    that.highlight(null);
  });

  google.maps.event.addListener(map, 'click', function() {
    that.highlight(null);
    that.infoWindow_.close();
  });
};

/**
 * Adds/remove hooks as appropriate.
 */
storeLocator.View.prototype.updateOnPan_changed = function() {
  if (this.updateOnPanListener_) {
    google.maps.event.removeListener(this.updateOnPanListener_);
  }

  if (this.get('updateOnPan') && this.getMap()) {
    var that = this;
    var map = this.getMap();
    this.updateOnPanListener_ = google.maps.event.addListener(map, 'idle',
        function() {
          that.refreshView();
        });
  }
};

/**
 * Add a store to the map.
 * @param {storeLocator.Store} store the store to add.
 */
storeLocator.View.prototype.addStoreToMap = function(store) {
  var marker = this.getMarker(store);
  store.setMarker(marker);
  var that = this;

  marker.clickListener_ = google.maps.event.addListener(marker, 'click',
      function() {
        that.highlight(store, false);
      });

  if (marker.getMap() != this.getMap()) {
    marker.setMap(this.getMap());
  }
};

/**
 * Create a marker for a store.
 * @param {storeLocator.Store} store the store to produce a marker for.
 * @this storeLocator.View
 * @return {google.maps.Marker} a new marker.
 * @export
 */
storeLocator.View.prototype.createMarker = function(store) {
  var markerOptions = {
    position: store.getLocation()
  };
  var opt_icon = this.settings_['markerIcon'];
  if (opt_icon) {
    markerOptions.icon = opt_icon;
  }
  return new google.maps.Marker(markerOptions);
};

/**
 * Get a marker for a store. By default, this caches the value from
 * createMarker(store)
 * @param {storeLocator.Store} store the store to get the marker from.
 * @return {google.maps.Marker} the marker.
 */
storeLocator.View.prototype.getMarker = function(store) {
  var cache = this.markerCache_;
  var key = store.getId();
  if (!cache[key]) {
    cache[key] = this['createMarker'](store);
  }
  return cache[key];
};

/**
 * Get a InfoWindow for a particular store.
 * @param {storeLocator.Store} store the store.
 * @return {google.maps.InfoWindow} the store's InfoWindow.
 */
storeLocator.View.prototype.getInfoWindow = function(store) {
  if (!store) {
    return this.infoWindow_;
  }

  var div = $(store.getInfoWindowContent());
  this.infoWindow_.setContent(div[0]);
  return this.infoWindow_;
};

/**
 * Gets all possible features for this View.
 * @return {storeLocator.FeatureSet} All possible features.
 */
storeLocator.View.prototype.getFeatures = function() {
  return this.settings_['features'];
};

/**
 * Gets a feature by its id. Convenience method.
 * @param {string} id the feature's id.
 * @return {storeLocator.Feature|undefined} The feature, if the id is valid.
 * undefined if not.
 */
storeLocator.View.prototype.getFeatureById = function(id) {
  if (!this.featureById_) {
    this.featureById_ = {};
    for (var i = 0, feature; feature = this.settings_['features'][i]; i++) {
      this.featureById_[feature.getId()] = feature;
    }
  }
  return this.featureById_[id];
};

/**
 * featureFilter_changed event handler.
 */
storeLocator.View.prototype.featureFilter_changed = function() {
  google.maps.event.trigger(this, 'featureFilter_changed',
      this.get('featureFilter'));

  if (this.get('stores')) {
    this.clearMarkers();
  }
};

/**
 * Clears the visible markers on the map.
 */
storeLocator.View.prototype.clearMarkers = function() {
  for (var marker in this.markerCache_) {
    this.markerCache_[marker].setMap(null);
    var listener = this.markerCache_[marker].clickListener_;
    if (listener) {
      google.maps.event.removeListener(listener);
    }
  }
};

/**
 * Refresh the map's view. This will fetch new data based on the map's bounds.
 */
storeLocator.View.prototype.refreshView = function() {
  var that = this;

  this.data_.getStores(this.getMap().getBounds(),
      (/** @type {storeLocator.FeatureSet} */ this.get('featureFilter')),
      function(stores) {
        var oldStores = that.get('stores');
        if (oldStores) {
          for (var i = 0, ii = oldStores.length; i < ii; i++) {
            google.maps.event.removeListener(
                oldStores[i].getMarker().clickListener_);
          }
        }
        that.set('stores', stores);
      });
};

/**
 * stores_changed event handler.
 * This will display all new stores on the map.
 * @this storeLocator.View
 */
storeLocator.View.prototype.stores_changed = function() {
  var stores = this.get('stores');
  for (var i = 0, store; store = stores[i]; i++) {
    this.addStoreToMap(store);
  }
};

/**
 * Gets the view's Map.
 * @return {google.maps.Map} the view's Map.
 */
storeLocator.View.prototype.getMap = function() {
  return this.map_;
};

/**
 * Select a particular store.
 * @param {storeLocator.Store} store the store to highlight.
 * @param {boolean} pan if panning to the store on the map is desired.
 */
storeLocator.View.prototype.highlight = function(store, pan) {
  var infoWindow = this.getInfoWindow(store);
  if (store) {
    var infoWindow = this.getInfoWindow(store);
    if (store.getMarker()) {
      infoWindow.open(this.getMap(), store.getMarker());
    } else {
      infoWindow.setPosition(store.getLocation());
      infoWindow.open(this.getMap());
    }
    if (pan) {
      this.getMap().panTo(store.getLocation());
    }
    if (this.getMap().getStreetView().getVisible()) {
      this.getMap().getStreetView().setPosition(store.getLocation());
    }
  } else {
    infoWindow.close();
  }

  this.set('selectedStore', store);
};

/**
 * Re-triggers the selectedStore_changed event with the store as a parameter.
 * @this storeLocator.View
 */
storeLocator.View.prototype.selectedStore_changed = function() {
  google.maps.event.trigger(this, 'selectedStore_changed',
      this.get('selectedStore'));
};

/**
 * Fired when the <code>View</code> is loaded. This happens once immediately,
 * then once more if geolocation is successful.
 * @name storeLocator.View#event:load
 * @event
 */

/**
 * Fired when the <code>View</code>'s <code>featureFilter</code> property
 * changes.
 * @name storeLocator.View#event:featureFilter_changed
 * @event
 */

/**
 * Fired when the <code>View</code>'s <code>updateOnPan</code> property changes.
 * @name storeLocator.View#event:updateOnPan_changed
 * @event
 */

/**
 * Fired when the <code>View</code>'s <code>stores</code> property changes.
 * @name storeLocator.View#event:stores_changed
 * @event
 */

/**
 * Fired when the <code>View</code>'s <code>selectedStore</code> property
 * changes. This happens after <code>highlight()</code> is called.
 * @name storeLocator.View#event:selectedStore_changed
 * @param {storeLocator.Store} store
 * @event
 */

/**
 * @example see storeLocator.View
 * @interface
 */
storeLocator.ViewOptions = function() {};

/**
 * Whether the map should update stores in the visible area when the visible
 * area changes. <code>refreshView()</code> will need to be called
 * programatically. Defaults to true.
 * @type boolean
 */
storeLocator.ViewOptions.prototype.updateOnPan;

/**
 * Whether the store locator should attempt to determine the user's location
 * for the initial view. Defaults to true.
 * @type boolean
 */
storeLocator.ViewOptions.prototype.geolocation;

/**
 * All available store features. Defaults to empty FeatureSet.
 * @type storeLocator.FeatureSet
 */
storeLocator.ViewOptions.prototype.features;

/**
 * The icon to use for markers representing stores.
 * @type string|google.maps.MarkerImage
 */
storeLocator.ViewOptions.prototype.markerIcon;

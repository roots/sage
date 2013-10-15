// Copyright 2012 Google Inc.

/**
 * @author Chris Broadfoot (Google)
 * @fileoverview
 * Allows developers to specify a static set of stores to be used in the
 * storelocator.
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
 * A DataFeed with a static set of stores. Provides sorting of stores by
 * proximity and feature filtering (store must have <em>all</em> features from
 * the filter).
 * @example <pre>
 * var dataFeed = new storeLocator.StaticDataFeed();
 * jQuery.getJSON('stores.json', function(json) {
 *   var stores = parseStores(json);
 *   dataFeed.setStores(stores);
 * });
 * new storeLocator.View(map, dataFeed);
 * </pre>
 * @implements {storeLocator.DataFeed}
 * @constructor
 * @implements storeLocator_StaticDataFeed
 */
storeLocator.StaticDataFeed = function() {
  /**
   * The static list of stores.
   * @private
   * @type {Array.<storeLocator.Store>}
   */
  this.stores_ = [];
};
storeLocator['StaticDataFeed'] = storeLocator.StaticDataFeed;

/**
 * This will contain a callback to be called if getStores was called before
 * setStores (i.e. if the map is waiting for data from the data source).
 * @private
 * @type {Function}
 */
storeLocator.StaticDataFeed.prototype.firstCallback_;

/**
 * Set the stores for this data feed.
 * @param {!Array.<!storeLocator.Store>} stores  the stores for this data feed.
 */
storeLocator.StaticDataFeed.prototype.setStores = function(stores) {
  this.stores_ = stores;
  if (this.firstCallback_) {
    this.firstCallback_();
  } else {
    delete this.firstCallback_;
  }
};

/**
 * @inheritDoc
 */
storeLocator.StaticDataFeed.prototype.getStores = function(bounds, features,
    callback) {

  // Prevent race condition - if getStores is called before stores are loaded.
  if (!this.stores_.length) {
    var that = this;
    this.firstCallback_ = function() {
      that.getStores(bounds, features, callback);
    };
    return;
  }

  // Filter stores for features.
  var stores = [];
  for (var i = 0, store; store = this.stores_[i]; i++) {
    if (store.hasAllFeatures(features)) {
      stores.push(store);
    }
  }
  this.sortByDistance_(bounds.getCenter(), stores);
  callback(stores);
};

/**
 * Sorts a list of given stores by distance from a point in ascending order.
 * Directly manipulates the given array (has side effects).
 * @private
 * @param {google.maps.LatLng} latLng the point to sort from.
 * @param {!Array.<!storeLocator.Store>} stores  the stores to sort.
 */
storeLocator.StaticDataFeed.prototype.sortByDistance_ = function(latLng,
    stores) {
  stores.sort(function(a, b) {
    return a.distanceTo(latLng) - b.distanceTo(latLng);
  });
};

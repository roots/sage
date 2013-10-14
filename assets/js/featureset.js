// Copyright 2012 Google Inc.

/**
 * @author Chris Broadfoot (Google)
 * @fileoverview
 * FeatureSet class for Store Locator library. A mutable, ordered set of
 * storeLocator.Features.
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
 * A mutable, ordered set of <code>storeLocator.Feature</code>s.
 * @example <pre>
 * var feature1 = new storeLocator.Feature('1', 'Feature One');
 * var feature2 = new storeLocator.Feature('2', 'Feature Two');
 * var feature3 = new storeLocator.Feature('3', 'Feature Three');
 *
 * var featureSet = new storeLocator.FeatureSet(feature1, feature2, feature3);
 * </pre>
 * @param {...storeLocator.Feature} var_args the initial features to add to
 * the set.
 * @constructor
 * @implements storeLocator_FeatureSet
 */
storeLocator.FeatureSet = function(var_args) {
  /**
   * Stores references to the actual Feature.
   * @private
   * @type {!Array.<storeLocator.Feature>}
   */
  this.array_ = [];

  /**
   * Maps from a Feature's id to its array index.
   * @private
   * @type {Object.<string, number>}
   */
  this.hash_ = {};

  for (var i = 0, feature; feature = arguments[i]; i++) {
    this.add(feature);
  }
};
storeLocator['FeatureSet'] = storeLocator.FeatureSet;

/**
 * Adds the given feature to the set, if it doesn't exist in the set already.
 * Else, removes the feature from the set.
 * @param {!storeLocator.Feature} feature the feature to toggle.
 */
storeLocator.FeatureSet.prototype.toggle = function(feature) {
  if (this.contains(feature)) {
    this.remove(feature);
  } else {
    this.add(feature);
  }
};

/**
 * Check if a feature exists within this set.
 * @param {!storeLocator.Feature} feature the feature.
 * @return {boolean} true if the set contains the given feature.
 */
storeLocator.FeatureSet.prototype.contains = function(feature) {
  return feature.getId() in this.hash_;
};

/**
 * Gets a Feature object from the set, by the feature id.
 * @param {string} featureId the feature's id.
 * @return {storeLocator.Feature} the feature, if the set contains it.
 */
storeLocator.FeatureSet.prototype.getById = function(featureId) {
  if (featureId in this.hash_) {
    return this.array_[this.hash_[featureId]];
  }
  return null;
};

/**
 * Adds a feature to the set.
 * @param {storeLocator.Feature} feature the feature to add.
 */
storeLocator.FeatureSet.prototype.add = function(feature) {
  if (!feature) {
    return;
  }
  this.array_.push(feature);
  this.hash_[feature.getId()] = this.array_.length - 1;
};

/**
 * Removes a feature from the set, if it already exists in the set. If it does
 * not already exist in the set, this function is a no op.
 * @param {!storeLocator.Feature} feature the feature to remove.
 */
storeLocator.FeatureSet.prototype.remove = function(feature) {
  if (!this.contains(feature)) {
    return;
  }
  this.array_[this.hash_[feature.getId()]] = null;
  delete this.hash_[feature.getId()];
};

/**
 * Get the contents of this set as an Array.
 * @return {Array.<!storeLocator.Feature>} the features in the set, in the order
 * they were inserted.
 */
storeLocator.FeatureSet.prototype.asList = function() {
  var filtered = [];
  for (var i = 0, ii = this.array_.length; i < ii; i++) {
    var elem = this.array_[i];
    if (elem !== null) {
      filtered.push(elem);
    }
  }
  return filtered;
};

/**
 * Empty feature set.
 * @type storeLocator.FeatureSet
 * @const
 */
storeLocator.FeatureSet.NONE = new storeLocator.FeatureSet;

// Copyright 2012 Google Inc.

/**
 * @author Chris Broadfoot (Google)
 * @fileoverview
 * Feature model class for Store Locator library.
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
 * Representation of a feature of a store. (e.g. 24 hours, BYO, etc).
 * @example <pre>
 * var feature = new storeLocator.Feature('24hour', 'Open 24 Hours');
 * </pre>
 * @param {string} id unique identifier for this feature.
 * @param {string} name display name of this feature.
 * @constructor
 * @implements storeLocator_Feature
 */
storeLocator.Feature = function(id, name) {
  this.id_ = id;
  this.name_ = name;
};
storeLocator['Feature'] = storeLocator.Feature;

/**
 * Gets this Feature's ID.
 * @return {string} this feature's ID.
 */
storeLocator.Feature.prototype.getId = function() {
  return this.id_;
};

/**
 * Gets this Feature's display name.
 * @return {string} this feature's display name.
 */
storeLocator.Feature.prototype.getDisplayName = function() {
  return this.name_;
};

storeLocator.Feature.prototype.toString = function() {
  return this.getDisplayName();
};

// Copyright 2012 Google Inc.

/**
 * @name Store Locator for Google Maps API V3
 * @version 0.1
 * @author Chris Broadfoot (Google)
 * @fileoverview
 * This library makes it easy to create a fully-featured Store Locator for
 * your business's website.
 */

/**
 * @license
 *
 * Copyright 2012 Google Inc.
 *
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
 * Namespace for Store Locator.
 * @constructor
 */
var storeLocator = function() {};
window['storeLocator'] = storeLocator;

/**
 * Convert from degrees to radians.
 * @private
 * @param {number} degrees the number in degrees.
 * @return {number} the number in radians.
 */
storeLocator.toRad_ = function(degrees) {
  return degrees * Math.PI / 180;
};

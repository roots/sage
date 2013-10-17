// Copyright 2012 Google Inc.

/**
 * @author Chris Broadfoot (Google)
 * @fileoverview
 * An info panel, which complements the map view of the Store Locator.
 * Provides a list of stores, location search, feature filter, and directions.
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
 * An info panel, to complement the map view.
 * Provides a list of stores, location search, feature filter, and directions.
 * @example <pre>
 * var container = document.getElementById('panel');
 * var panel = new storeLocator.Panel(container, {
 *   view: view,
 *   locationSearchLabel: 'Location:'
 * });
 * google.maps.event.addListener(panel, 'geocode', function(result) {
 *   geocodeMarker.setPosition(result.geometry.location);
 * });
 * </pre>
 * @extends {google.maps.MVCObject}
 * @param {!Node} el the element to contain this panel.
 * @param {storeLocator.PanelOptions} opt_options
 * @constructor
 * @implements storeLocator_Panel
 */
storeLocator.Panel = function(el, opt_options) {
  this.el_ = $(el);
  this.el_.addClass('storelocator-panel');
  this.settings_ = $.extend({
      'locationSearch': true,
      'locationSearchLabel': 'Where are you?',
      'featureFilter': true,
      'directions': true,
      'view': null
    }, opt_options);

  this.directionsRenderer_ = new google.maps.DirectionsRenderer({
    draggable: true
  });
  this.directionsService_ = new google.maps.DirectionsService;

  this.init_();
};
storeLocator['Panel'] = storeLocator.Panel;

storeLocator.Panel.prototype = new google.maps.MVCObject;

/**
 * Initialise the info panel
 * @private
 */
storeLocator.Panel.prototype.init_ = function() {
  var that = this;
  this.itemCache_ = {};

  if (this.settings_['view']) {
    this.set('view', this.settings_['view']);
  }

  this.filter_ = $('<form class="storelocator-filter"/>');
  this.el_.append(this.filter_);

  if (this.settings_['locationSearch']) {
    this.locationSearch_ = $('<div class="location-search"><h4>' +
        this.settings_['locationSearchLabel'] + '</h4><input></div>');
    this.filter_.append(this.locationSearch_);

    if (typeof google.maps.places != 'undefined') {
      this.initAutocomplete_();
    } else {
      this.filter_.submit(function() {
        that.searchPosition($('input', that.locationSearch_).val());
      });
    }
    this.filter_.submit(function() {
      return false;
    });

    google.maps.event.addListener(this, 'geocode', function(place) {
      if (that.searchPositionTimeout_) {
        window.clearTimeout(that.searchPositionTimeout_);
      }
      if (!place.geometry) {
        that.searchPosition(place.name);
        return;
      }

      this.directionsFrom_ = place.geometry.location;

      if (that.directionsVisible_) {
        that.renderDirections_();
      }
      var sl = that.get('view');
      sl.highlight(null);
      var map = sl.getMap();
      if (place.geometry.viewport) {
        map.fitBounds(place.geometry.viewport);
      } else {
        map.setCenter(place.geometry.location);
        map.setZoom(13);
      }
      sl.refreshView();
      that.listenForStoresUpdate_();
    });
  }

  if (this.settings_['featureFilter']) {
    // TODO(cbro): update this on view_changed
    this.featureFilter_ = $('<div class="feature-filter"/>');
    var allFeatures = this.get('view').getFeatures().asList();
    for (var i = 0, ii = allFeatures.length; i < ii; i++) {
      var feature = allFeatures[i];
      var checkbox = $('<input type="checkbox"/>');
      checkbox.data('feature', feature);
      $('<label/>').append(checkbox).append(feature.getDisplayName())
        .appendTo(this.featureFilter_);
    }
    this.filter_.append(this.featureFilter_);
    this.featureFilter_.find('input').change(function() {
      var feature = $(this).data('feature');
      that.toggleFeatureFilter_(feature);
      that.get('view').refreshView();
    });
  }

  this.storeList_ = $('<ul class="store-list"/>');
  this.el_.append(this.storeList_);

  if (this.settings_['directions']) {
    this.directionsPanel_ = $('<div class="directions-panel"><form>' +
        '<input class="directions-to"/>' +
        '<input type="submit" value="Find directions"/>' +
        '<a href="#" class="close-directions">Close</a>' +
        '</form><div class="rendered-directions"></div></div>');
    this.directionsPanel_.find('.directions-to').attr('readonly', 'readonly');
    this.directionsPanel_.hide();
    this.directionsVisible_ = false;
    this.directionsPanel_.find('form').submit(function() {
      that.renderDirections_();
      return false;
    });
    this.directionsPanel_.find('.close-directions').click(function() {
      that.hideDirections();
    });
    this.el_.append(this.directionsPanel_);
  }
};

/**
 * Toggles a particular feature on/off in the feature filter.
 * @param {storeLocator.Feature} feature The feature to toggle.
 * @private
 */
storeLocator.Panel.prototype.toggleFeatureFilter_ = function(feature) {
  var featureFilter = this.get('featureFilter');
  featureFilter.toggle(feature);
  this.set('featureFilter', featureFilter);
};

/**
 * Global Geocoder instance, for convenience.
 * @type {google.maps.Geocoder}
 * @private
 */
storeLocator.geocoder_ = new google.maps.Geocoder;

/**
 * Triggers an update for the store list in the Panel. Will wait for stores
 * to load asynchronously from the data source.
 * @private
 */
storeLocator.Panel.prototype.listenForStoresUpdate_ = function() {
  var that = this;
  var view = /** @type storeLocator.View */(this.get('view'));
  if (this.storesChangedListener_) {
    google.maps.event.removeListener(this.storesChangedListener_);
  }
  this.storesChangedListener_ = google.maps.event.addListenerOnce(view,
      'stores_changed', function() {
        that.set('stores', view.get('stores'));
      });
};
/**
 * Search and pan to the specified address.
 * @param {string} searchText the address to pan to.
 */
storeLocator.Panel.prototype.searchPosition = function(searchText) {
  var that = this;
  var request = {
    address: searchText,
    bounds: this.get('view').getMap().getBounds()
  };
  storeLocator.geocoder_.geocode(request, function(result, status) {
    if (status != google.maps.GeocoderStatus.OK) {
      //TODO(cbro): proper error handling
      return;
    }
    google.maps.event.trigger(that, 'geocode', result[0]);
  });
};

/**
 * Sets the associated View.
 * @param {storeLocator.View} view the view to set.
 */
storeLocator.Panel.prototype.setView = function(view) {
  this.set('view', view);
};

/**
 * view_changed handler.
 * Sets up additional bindings between the info panel and the map view.
 */
storeLocator.Panel.prototype.view_changed = function() {
  var sl = /** @type {google.maps.MVCObject} */ (this.get('view'));
  this.bindTo('selectedStore', sl);

  var that = this;
  if (this.geolocationListener_) {
    google.maps.event.removeListener(this.geolocationListener_);
  }
  if (this.zoomListener_) {
    google.maps.event.removeListener(this.zoomListener_);
  }
  if (this.idleListener_) {
    google.maps.event.removeListener(this.idleListener_);
  }

  var center = sl.getMap().getCenter();

  var updateList = function() {
    sl.clearMarkers();
    that.listenForStoresUpdate_();
  };

  //TODO(cbro): somehow get the geolocated position and populate the 'from' box.
  this.geolocationListener_ = google.maps.event.addListener(sl, 'load',
      updateList);

  this.zoomListener_ = google.maps.event.addListener(sl.getMap(),
      'zoom_changed', updateList);

  this.idleListener_ = google.maps.event.addListener(sl.getMap(),
      'idle', function() {
        return that.idle_(sl.getMap());
      });

  updateList();
  this.bindTo('featureFilter', sl);

  if (this.autoComplete_) {
    this.autoComplete_.bindTo('bounds', sl.getMap());
  }
};

/**
 * Adds autocomplete to the input box.
 * @private
 */
storeLocator.Panel.prototype.initAutocomplete_ = function() {
  var that = this;
  var input = $('input', this.locationSearch_)[0];
  this.autoComplete_ = new google.maps.places.Autocomplete(input);
  if (this.get('view')) {
    this.autoComplete_.bindTo('bounds', this.get('view').getMap());
  }
  google.maps.event.addListener(this.autoComplete_, 'place_changed',
      function() {
        google.maps.event.trigger(that, 'geocode', this.getPlace());
      });
};

/**
 * Called on the view's map idle event. Refreshes the store list if the
 * user has navigated far away enough.
 * @param {google.maps.Map} map the current view's map.
 * @private
 */
storeLocator.Panel.prototype.idle_ = function(map) {
  if (!this.center_) {
    this.center_ = map.getCenter();
  } else if (!map.getBounds().contains(this.center_)) {
    this.center_ = map.getCenter();
    this.listenForStoresUpdate_();
  }
};

/**
 * @const
 * @type {string}
 * @private
 */
storeLocator.Panel.NO_STORES_HTML_ = '<li class="no-stores">There are no' +
    ' stores in this area.</li>';

/**
 * @const
 * @type {string}
 * @private
 */
storeLocator.Panel.NO_STORES_IN_VIEW_HTML_ = '<li class="no-stores">There are' +
    ' no stores in this area. However, stores closest to you are' +
    ' listed below.</li>';
/**
 * Handler for stores_changed. Updates the list of stores.
 * @this storeLocator.Panel
 */
storeLocator.Panel.prototype.stores_changed = function() {
  if (!this.get('stores')) {
    return;
  }

  var view = this.get('view');
  var bounds = view && view.getMap().getBounds();

  var that = this;
  var stores = this.get('stores');
  var selectedStore = this.get('selectedStore');
  this.storeList_.empty();

  if (!stores.length) {
    this.storeList_.append(storeLocator.Panel.NO_STORES_HTML_);
  } else if (bounds && !bounds.contains(stores[0].getLocation())) {
    this.storeList_.append(storeLocator.Panel.NO_STORES_IN_VIEW_HTML_);
  }

  var clickHandler = function() {
    view.highlight(this['store'], true);
  };

  // TODO(cbro): change 10 to a setting/option
  for (var i = 0, ii = Math.min(10, stores.length); i < ii; i++) {
    var storeLi = stores[i].getInfoPanelItem();
    storeLi['store'] = stores[i];
    if (selectedStore && stores[i].getId() == selectedStore.getId()) {
      $(storeLi).addClass('highlighted');
    }

    if (!storeLi.clickHandler_) {
      storeLi.clickHandler_ = google.maps.event.addDomListener(
          storeLi, 'click', clickHandler);
    }

    that.storeList_.append(storeLi);
  }
};

/**
 * Handler for selectedStore_changed. Highlights the selected store in the
 * store list.
 * @this storeLocator.Panel
 */
storeLocator.Panel.prototype.selectedStore_changed = function() {
  $('.highlighted', this.storeList_).removeClass('highlighted');

  var that = this;
  var store = this.get('selectedStore');
  if (!store) {
    return;
  }
  this.directionsTo_ = store;
  this.storeList_.find('#store-' + store.getId()).addClass('highlighted');

  if (this.settings_['directions']) {
    this.directionsPanel_.find('.directions-to')
      .val(store.getDetails().title);
  }

  var node = that.get('view').getInfoWindow().getContent();
  var directionsLink = $('<a/>')
                          .text('Directions')
                          .attr('href', '#')
                          .addClass('action')
                          .addClass('directions');

  // TODO(cbro): Make these two permanent fixtures in InfoWindow.
  // Move out of Panel.
  var zoomLink = $('<a/>')
                    .text('Zoom here')
                    .attr('href', '#')
                    .addClass('action')
                    .addClass('zoomhere');

  var streetViewLink = $('<a/>')
                          .text('Street view')
                          .attr('href', '#')
                          .addClass('action')
                          .addClass('streetview');

  directionsLink.click(function() {
    that.showDirections();
    return false;
  });

  zoomLink.click(function() {
    that.get('view').getMap().setOptions({
      center: store.getLocation(),
      zoom: 16
    });
  });

  streetViewLink.click(function() {
    var streetView = that.get('view').getMap().getStreetView();
    streetView.setPosition(store.getLocation());
    streetView.setVisible(true);
  });

  $(node).append(directionsLink).append(zoomLink).append(streetViewLink);
};

/**
 * Hides the directions panel.
 */
storeLocator.Panel.prototype.hideDirections = function() {
  this.directionsVisible_ = false;
  this.directionsPanel_.fadeOut();
  this.featureFilter_.fadeIn();
  this.storeList_.fadeIn();
  this.directionsRenderer_.setMap(null);
};

/**
 * Shows directions to the selected store.
 */
storeLocator.Panel.prototype.showDirections = function() {
  var store = this.get('selectedStore');
  this.featureFilter_.fadeOut();
  this.storeList_.fadeOut();
  this.directionsPanel_.find('.directions-to').val(store.getDetails().title);
  this.directionsPanel_.fadeIn();
  this.renderDirections_();

  this.directionsVisible_ = true;
};

/**
 * Renders directions from the location in the input box, to the store that is
 * pre-filled in the 'to' box.
 * @private
 */
storeLocator.Panel.prototype.renderDirections_ = function() {
  var that = this;
  if (!this.directionsFrom_ || !this.directionsTo_) {
    return;
  }
  var rendered = this.directionsPanel_.find('.rendered-directions').empty();

  this.directionsService_.route({
    origin: this.directionsFrom_,
    destination: this.directionsTo_.getLocation(),
    travelMode: google.maps['DirectionsTravelMode'].DRIVING
    //TODO(cbro): region biasing, waypoints, travelmode
  }, function(result, status) {
    if (status != google.maps.DirectionsStatus.OK) {
      // TODO(cbro): better error handling
      return;
    }

    var renderer = that.directionsRenderer_;
    renderer.setPanel(rendered[0]);
    renderer.setMap(that.get('view').getMap());
    renderer.setDirections(result);
  });
};

/**
 * featureFilter_changed event handler.
 */
storeLocator.Panel.prototype.featureFilter_changed = function() {
  this.listenForStoresUpdate_();
};

/**
 * Fired when searchPosition has been called. This happens when the user has
 * searched for a location from the location search box and/or autocomplete.
 * @name storeLocator.Panel#event:geocode
 * @param {google.maps.PlaceResult|google.maps.GeocoderResult} result
 * @event
 */

/**
 * Fired when the <code>Panel</code>'s <code>view</code> property changes.
 * @name storeLocator.Panel#event:view_changed
 * @event
 */

/**
 * Fired when the <code>Panel</code>'s <code>featureFilter</code> property
 * changes.
 * @name storeLocator.Panel#event:featureFilter_changed
 * @event
 */

/**
 * Fired when the <code>Panel</code>'s <code>stores</code> property changes.
 * @name storeLocator.Panel#event:stores_changed
 * @event
 */

/**
 * Fired when the <code>Panel</code>'s <code>selectedStore</code> property
 * changes.
 * @name storeLocator.Panel#event:selectedStore_changed
 * @event
 */

/**
 * @example see storeLocator.Panel
 * @interface
 */
storeLocator.PanelOptions = function() {};

/**
 * Whether to show the location search box. Default is true.
 * @type boolean
 */
storeLocator.prototype.locationSearch;

/**
 * The label to show above the location search box. Default is "Where are you
 * now?".
 * @type string
 */
storeLocator.PanelOptions.prototype.locationSearchLabel;

/**
 * Whether to show the feature filter picker. Default is true.
 * @type boolean
 */
storeLocator.PanelOptions.prototype.featureFilter;

/**
 * Whether to provide directions. Deafult is true.
 * @type boolean
 */
storeLocator.PanelOptions.prototype.directions;

/**
 * The store locator model to bind to.
 * @type storeLocator.View
 */
storeLocator.PanelOptions.prototype.view;

/**
 * @implements storeLocator.DataFeed
 * @constructor
 */
function MedicareDataSource() {
}

MedicareDataSource.prototype.getStores = function(bounds, features, callback) {
  var center = bounds.getCenter();
  var that = this;
  var audioFeature = this.FEATURES_.getById('Audio-YES');
  var wheelchairFeature = this.FEATURES_.getById('Wheelchair-YES');

  $.getJSON('https://storelocator-go-demo.appspot.com/query?callback=?', {
    lat: center.lat(),
    lng: center.lng(),
    n: bounds.getNorthEast().lat(),
    e: bounds.getNorthEast().lng(),
    s: bounds.getSouthWest().lat(),
    w: bounds.getSouthWest().lng(),
    audio: features.contains(audioFeature) || '',
    access: features.contains(wheelchairFeature) || ''
  }, function(resp) {
    var stores = that.parse_(resp.data);
    that.sortByDistance_(center, stores);
    callback(stores);
  });
};

MedicareDataSource.prototype.parse_ = function(data) {
  var stores = [];
  for (var i = 0, row; row = data[i]; i++) {
    var features = new storeLocator.FeatureSet;
    features.add(this.FEATURES_.getById('Wheelchair-' + row.Wheelchair));
    features.add(this.FEATURES_.getById('Audio-' + row.Audio));

    var position = new google.maps.LatLng(row.Ycoord, row.Xcoord);

    var shop = this.join_([row.Shp_num_an, row.Shp_centre], ', ');
    var locality = this.join_([row.Locality, row.Postcode], ', ');

    var store = new storeLocator.Store(row.uuid, position, features, {
      title: row.Fcilty_nam,
      address: this.join_([shop, row.Street_add, locality], '<br>'),
      hours: row.Hrs_of_bus
    });
    stores.push(store);
  }
  return stores;
};

/**
 * @const
 * @type {!storeLocator.FeatureSet}
 * @private
 */
MedicareDataSource.prototype.FEATURES_ = new storeLocator.FeatureSet(
  new storeLocator.Feature('Wheelchair-YES', 'Wheelchair access'),
  new storeLocator.Feature('Audio-YES', 'Audio')
);

/**
 * @return {!storeLocator.FeatureSet}
 */
MedicareDataSource.prototype.getFeatures = function() {
  return this.FEATURES_;
};


/**
 * Joins elements of an array that are non-empty and non-null.
 * @private
 * @param {!Array} arr array of elements to join.
 * @param {string} sep the separator.
 * @return {string}
 */
MedicareDataSource.prototype.join_ = function(arr, sep) {
  var parts = [];
  for (var i = 0, ii = arr.length; i < ii; i++) {
    arr[i] && parts.push(arr[i]);
  }
  return parts.join(sep);
};

/**
 * Sorts a list of given stores by distance from a point in ascending order.
 * Directly manipulates the given array (has side effects).
 * @private
 * @param {google.maps.LatLng} latLng the point to sort from.
 * @param {!Array.<!storeLocator.Store>} stores  the stores to sort.
 */
MedicareDataSource.prototype.sortByDistance_ = function(latLng,
    stores) {
  stores.sort(function(a, b) {
    return a.distanceTo(latLng) - b.distanceTo(latLng);
  });
};

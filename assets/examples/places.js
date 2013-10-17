// A simple demo showing how to grab places using the Maps API Places Library.
// Useful extensions may include using "name" filtering or "keyword" search.

google.maps.event.addDomListener(window, 'load', function() {
  var map = new google.maps.Map(document.getElementById('map-canvas'), {
    center: new google.maps.LatLng(-28, 135),
    zoom: 4,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });

  var panelDiv = document.getElementById('panel');

  var data = new PlacesDataSource(map);

  var view = new storeLocator.View(map, data);

  var markerSize = new google.maps.Size(24, 24);
  view.createMarker = function(store) {
    return new google.maps.Marker({
      position: store.getLocation(),
      icon: new google.maps.MarkerImage(store.getDetails().icon, null, null,
          null, markerSize)
    });
  };

  new storeLocator.Panel(panelDiv, {
    view: view
  });
});

/**
 * Creates a new PlacesDataSource.
 * @param {google.maps.Map} map
 * @constructor
 */
function PlacesDataSource(map) {
  this.service_ = new google.maps.places.PlacesService(map);
}

/**
 * @inheritDoc
 */
PlacesDataSource.prototype.getStores = function(bounds, features, callback) {
  this.service_.search({
    bounds: bounds
  }, function(results, status) {
    var stores = [];

    for (var i = 0, result; result = results[i]; i++) {
      var latLng = result.geometry.location;
      var store = new storeLocator.Store(result.id, latLng, null, {
        title: result.name,
        address: result.types.join(', '),
        icon: result.icon
      });
      stores.push(store);
    }

    callback(stores);
  });
};

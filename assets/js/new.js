google.maps.event.addDomListener(window, 'load', function() {
  var map = new google.maps.Map(document.getElementById('map-canvas'), {
    center: new google.maps.LatLng(-28, 135),
    zoom: 4,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });

  var panelDiv = document.getElementById('panel');

  var data = new MedicareDataSource;

  var view = new storeLocator.View(map, data, {
    geolocation: false,
    features: data.getFeatures()
  });

  new storeLocator.Panel(panelDiv, {
    view: view
  });
});

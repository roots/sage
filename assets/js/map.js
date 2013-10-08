var afchome = new google.maps.LatLng(35.2214777, -97.445885);

var infowindow = new google.maps.InfoWindow();
var atkoremarker = new google.maps.MarkerImage('/media/map-marker-atkore-green.png', new google.maps.Size(32, 37) );
var kaftechmarker = new google.maps.MarkerImage('/media/map-marker-kaftech-blue.png', new google.maps.Size(32, 37) );
var afcmarkerblue = new google.maps.MarkerImage('/media/map-marker-afc-blue.png', new google.maps.Size(32, 37) );
var afcmarkergrey = new google.maps.MarkerImage('/media/map-marker-afc-grey.png', new google.maps.Size(32, 37) );
var alliedtubemarker = new google.maps.MarkerImage('/media/map-marker-alledtube-red.png', new google.maps.Size(32, 37) );
var easternwiremarker = new google.maps.MarkerImage('/media/map-marker-easternwire-blue.png', new google.maps.Size(32, 37) );
var unistrutmarker = new google.maps.MarkerImage('/media/map-marker-unistrut-blue.png', new google.maps.Size(32, 37) );

var poly;
var geodesicPoly;
var marker1;
var marker2;

function initialize() {

  var mapOptions = {
    zoom: 4,
    center: afchome,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    scrollwheel: false
  };

  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);



  var markers = [];
    for (var i = 0; i < locations.length; i++) {  

  var marker = new google.maps.Marker({
    position: locations[i].latlng,
    icon: afcmarkergrey,
    map: map
  });

  markers.push(marker);

  google.maps.event.addListener(marker, 'click', (function(marker, i) {

    return function() {
      infowindow.setContent(locations[i].info);
      infowindow.open(map, marker);
      }

    })(marker, i));

  }

}

google.maps.event.addDomListener(window, 'load', initialize);
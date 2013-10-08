var infowindow = new google.maps.InfoWindow();
var atkoremarker = new google.maps.MarkerImage('/media/map-marker-atkore-green.png', new google.maps.Size(32, 37) );
var kaftechmarker = new google.maps.MarkerImage('/media/map-marker-kaftech-blue.png', new google.maps.Size(32, 37) );
var afcmarker = new google.maps.MarkerImage('/media/map-marker-afc-blue.png', new google.maps.Size(32, 37) );
var alliedtubemarker = new google.maps.MarkerImage('/media/map-marker-alledtube-red.png', new google.maps.Size(32, 37) );
var easternwiremarker = new google.maps.MarkerImage('/media/map-marker-easternwire-blue.png', new google.maps.Size(32, 37) );
var unistrutmarker = new google.maps.MarkerImage('/media/map-marker-unistrut-blue.png', new google.maps.Size(32, 37) );

function initialize() {
	var map = new google.maps.Map(document.getElementById('map_canvas'), { 
		zoom: 4, 
		center: new google.maps.LatLng(35.2214777, -97.445885), 
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		scrollwheel: false
	});

    	var markers = [];
			for (var i = 0; i < locations.length; i++) {  

				var marker = new google.maps.Marker({
					position: locations[i].latlng,
					icon: afcmarker,
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
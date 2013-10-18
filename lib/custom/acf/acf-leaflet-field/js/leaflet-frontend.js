jQuery(document).ready(function($) {
    // only render the map if an api-key is present
    render_leaflet_map();

    function render_leaflet_map() {
        if( typeof leaflet_field.value == 'object' ) {
            map_settings = leaflet_field.value;
        }
        else {
            map_settings = {
                zoom_level:null,
                center:{
                    lat:null,
                    lng:null
                },
                markers:{}
            };
        }

        if( map_settings.center.lat == null ) {
            map_settings.center.lat = leaflet_field.lat;
        }

        if( map_settings.center.lng == null ) {
            map_settings.center.lng = leaflet_field.lng;
        }

        // check if the zoom level is set and within 1-18
        if( map_settings.zoom_level == null || map_settings.zoom_level > 18 || map_settings.zoom_level < 1 ) {
            if( leaflet_field.zoom_level > 0 && leaflet_field.zoom_level < 19 ) {
                map_settings.zoom_level = leaflet_field.zoom_level;
            }
            else {
                map_settings.zoom_level = 13;
            }
        }

        var map = L.map( leaflet_field.id + '_map', {
            center: new L.LatLng( map_settings.center.lat, map_settings.center.lng ),
            zoom: map_settings.zoom_level,
            doubleClickZoom: true
        });

        L.tileLayer(leaflet_field.map_provider.url, {
            attribution: leaflet_field.map_provider.attribution,
            maxZoom: 18
        }).addTo(map);

        if( Object.keys(map_settings.markers).length > 0 ) {
            $.each(map_settings.markers, function(index, marker) {
                L.geoJson(marker, {
                    onEachFeature:function(feature, layer){
                        if(feature.properties && feature.properties.popupContent && feature.properties.popupContent != "") {
                            layer.bindPopup(feature.properties.popupContent);
                        }
                    }
                }).addTo(map);
            });
        }
    }

});
(function () {

    var map;

    let initMap = function () {

        var northEast = L.latLng(boundsNorthEast),
        southWest = L.latLng(boundsSouthWest),
        bounds = L.latLngBounds(southWest, northEast);

        map = L.map('map', {
            minZoom: zoomMin,
            maxBounds: bounds
        }).setView([51.37975919204143, 10.541374896190414], zoomMax);

        map.scrollWheelZoom.disable();

        L.tileLayer(mapProvider, {
            bounds: bounds,
            attribution: attributionProvider
        }).addTo(map);

        map.on('click', function(e){
            var coord = e.latlng;
            var lat = coord.lat;
            var lng = coord.lng;
            console.log("You clicked the map at latitude: " + lat + " and longitude: " + lng);
        });
    };

    let nl2br = function (str, replaceMode, isXhtml) {
        var breakTag = (isXhtml) ? '<br />' : '<br>';
        var replaceStr = (replaceMode) ? '$1'+ breakTag : '$1'+ breakTag +'$2';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, replaceStr);
      }

    let drawMarkers = function() {

        var bounds = L.latLngBounds();

        for (var i in pois) {

            var type = ((pois[i].data.geo_type != '-1') ? marker[pois[i].data.geo_type] : 'blue');

            var costumIcon = L.icon({
                iconUrl: '/typo3conf/ext/maps/Resources/Public/Images/marker/marker-icon-' + type + '.png',
                shadowUrl: '/typo3conf/ext/maps/Resources/Public/Images/marker/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            var latlng = L.latLng({ lat: pois[i].data.geo_lat, lng: pois[i].data.geo_long });
            var title = pois[i].data.title;
            var subtitle = pois[i].data.subtitle;
            var abstract = pois[i].data.abstract;
            var address = pois[i].data.geo_address;
            var slug = pois[i].data.slug;

            L.marker( latlng, {icon: costumIcon})
                .bindTooltip(title, {permanent: false, direction: 'bottom', opacity: '0.8', offset: [0,0]})
                .bindPopup('<p><b><a href="' + slug + '">' + title + '</a></b><br><b>' + subtitle + '</b><br>' + abstract + '<br><small>' + nl2br(address)  + '</small></p>')
                .addTo(map);
            
                bounds.extend(latlng);
        }
        //map.fitBounds(bounds);
    }

    let drawMask = function() {
        L.mask(maskGeoJson, {fillOpacity: 1, fillColor: '#cccccc', stroke: true, color: '#cccccc', width: 1, opacity: 0.8}).addTo(map);
    }

    let initialize = function() {
        if (document.getElementById("map")) {
            console.log('Init map');
            initMap();
            drawMarkers();
            drawMask();
        }
    };

    if (document.readyState!='loading') initialize();
    else if (document.addEventListener) document.addEventListener('DOMContentLoaded', initialize);
})();

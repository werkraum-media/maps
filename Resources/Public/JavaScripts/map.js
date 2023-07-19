(function () {

    var map;

    let initMap = function () {

        var northEast = L.latLng(boundsNorthEast);
        var southWest = L.latLng(boundsSouthWest);
        var bounds = L.latLngBounds(southWest, northEast);

        map = L.map('map', {
            minZoom: zoomMin,
            maxBounds: bounds
        }).setView([51.37975919204143, 10.541374896190414], zoomMax);

        map.scrollWheelZoom.disable();

        L.tileLayer(mapProvider, {
            bounds: bounds,
            attribution: attributionProvider
        }).addTo(map);

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
            var pageTitle = ((pois[i].data.title) ? pois[i].data.title : '');
            var pageSubtitle = ((pois[i].data.subtitle) ? pois[i].data.subtitle : '');
            var pageAbstract = ((pois[i].data.abstract) ? pois[i].data.abstract : '');
            var pageSlug = ((pois[i].data.slug) ? pois[i].data.slug : '');
            var geoTitle = ((pois[i].data.geo_title) ? pois[i].data.geo_title : '');
            var geoSubitle = ((pois[i].data.geo_subtitle) ? pois[i].data.geo_subtitle : '');
            var geoAddress = ((pois[i].data.geo_address) ? pois[i].data.geo_address : '');
            

            L.marker( latlng, {icon: costumIcon})
                .bindTooltip(pageTitle, {permanent: false, direction: 'bottom', opacity: '0.8', offset: [0,0]})
                .bindPopup(`<p><b><a href="${pageSlug}">${pageTitle}</a></b><br><b>${pageSubtitle}</b><br>${pageAbstract}<br><small>${nl2br(geoAddress)}</small></p>`)
                .addTo(map);
            
                bounds.extend(latlng);
        }
        //map.fitBounds(bounds);
    }

    let drawMask = function() {
        L.mask(maskGeoJson, {
            fillOpacity: 1, 
            fillColor: '#cc00cc', 
            stroke: true, 
            color: '#cc00cc', 
            width: 1, 
            opacity: 1
            }
        ).addTo(map);
    }

    let initialize = function() {
        if (document.getElementById("map")) {
            initMap();
            drawMarkers();
            drawMask();
        }
    };

    if (document.readyState!='loading') initialize();
    else if (document.addEventListener) document.addEventListener('DOMContentLoaded', initialize);
})();

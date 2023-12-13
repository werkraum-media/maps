(function () {

    let initMap = function (mapElement) {

        var map;

        var northEast = L.latLng(eval(mapElement.getAttribute("data-bounds-north-east")));
        var southWest = L.latLng(eval(mapElement.getAttribute("data-bounds-south-west")));
        var bounds = L.latLngBounds(southWest, northEast);
        var zoomMin = mapElement.getAttribute("data-zoom-min");
        var zoomMax = mapElement.getAttribute("data-zoom-max");
        var pois = JSON.parse(mapElement.getAttribute("data-pois"));
        var marker = JSON.parse((mapElement.getAttribute("data-marker")));
        var maskGeoJson = mapElement.getAttribute("data-mask-geo-json");
        var maskColor = mapElement.getAttribute("data-mask-color");

        map = L.map(mapElement, {
            minZoom: zoomMin,
            maxZoom: zoomMax,
            maxBounds: bounds
        }).setView([51.37975919204143, 10.541374896190414], 8);

        map.scrollWheelZoom.disable();
        
        L.tileLayer(mapProvider, {
            bounds: bounds,
            attribution: attributionProvider
        }).addTo(map);

        if (pois != null && marker != null) {
            drawMarkers(map, pois, marker);
        }

        if (maskGeoJson != null && maskColor != null) {
            drawMask(map, maskGeoJson, maskColor);
        }

    };

    let drawMarkers = function(map, pois, marker) {

        var bounds = L.latLngBounds();

        for (var i in pois) {

            var type = ((pois[i].data.geo_type != '-1') ? marker[pois[i].data.geo_type] : 'blue');

            var costumIcon = L.icon({
                iconUrl: resourcePath + 'marker-icon-' + type + '.png',
                shadowUrl: resourcePath + 'marker-shadow.png',
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
            var geoSubtitle = ((pois[i].data.geo_subtitle) ? pois[i].data.geo_subtitle : '');
            var geoAddress = ((pois[i].data.geo_address) ? pois[i].data.geo_address : '');
            
            L.marker( latlng, {icon: costumIcon})
                .bindTooltip(pageTitle, {permanent: false, direction: 'bottom', opacity: '0.8', offset: [0,0]})
                .bindPopup(`<p>
                <b><a href="${pageSlug}">${geoTitle ? geoTitle : pageTitle}</a></b><br>
                <b>${geoSubtitle ? geoSubtitle : pageSubtitle}</b><br>
                ${pageAbstract}<br>
                <small>${nl2br(geoAddress)}</small></p>`)
                .addTo(map);
            
                bounds.extend(latlng);
        }

        map.fitBounds(bounds);

    }

    let drawMask = function(map, maskGeoJson, maskColor) {

        L.mask(maskGeoJson, {
            fillOpacity: 1, 
            fillColor: maskColor, 
            stroke: true, 
            color: maskColor, 
            width: 1, 
            opacity: 1
            }
        ).addTo(map);
    }

    let nl2br = function (str, replaceMode, isXhtml) {

        var breakTag = (isXhtml) ? '<br />' : '<br>';
        var replaceStr = (replaceMode) ? '$1'+ breakTag : '$1'+ breakTag +'$2';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, replaceStr);

    }

    let initialize = function() {

        if (elements = document.getElementsByClassName("map")) {
            for (var i = 0; i < elements.length; i++) {
                initMap(elements[i]);
            }
        }

    };

    if (document.readyState!='loading') initialize();
    else if (document.addEventListener) document.addEventListener('DOMContentLoaded', initialize);
})();

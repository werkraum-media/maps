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
        var clickBehaviour = mapElement.getAttribute("data-click-behaviour");
        var showTooltip = mapElement.getAttribute("data-show-tooltip");

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
            drawMarkers(map, pois, marker, clickBehaviour, showTooltip);
        }

        if (maskGeoJson != null && maskColor != null) {
            drawMask(map, maskGeoJson, maskColor);
        }

    };

    let drawMarkers = function(map, pois, marker, clickBehaviour, showTooltip) {

        var bounds = L.latLngBounds();
        var marker;

        for (var i in pois) {
            var poi = [];
            poi['uid'] = pois[i].data.uid;
            poi['type'] = ((pois[i].data.geo_type != '-1') ? marker[pois[i].data.geo_type] : 'blue');
            poi['latlng'] = L.latLng({ lat: pois[i].data.geo_lat, lng: pois[i].data.geo_long });
            poi['slug'] = ((pois[i].data.slug) ? pois[i].data.slug : null);
            poi['title'] = ((pois[i].data.geo_title) ? pois[i].data.geo_title : pois[i].data.title);
            poi['subtitle'] = ((pois[i].data.geo_subtitle) ? pois[i].data.geo_subtitle : ((pois[i].data.subtitle) ? pois[i].data.subtitle : null));
            poi['abstract'] = ((pois[i].data.abstract) ? pois[i].data.abstract : null);
            poi['address'] = ((pois[i].data.geo_address) ? pois[i].data.geo_address : null);
            poi['image'] = ((pois[i].files[0]) ? pois[i].files[0] : null);

            var costumIcon = L.icon({
                iconUrl: resourcePath + 'marker-icon-' + poi['type'] + '.png',
                shadowUrl: resourcePath + 'marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            marker = L.marker( poi['latlng'], {icon: costumIcon});

            if (showTooltip == 1) {
                marker.bindTooltip(poi['title'], {permanent: false, direction: 'bottom', opacity: '0.8', offset: [0,0]});
            }

            if (clickBehaviour == 'INFO') {
                marker.on('click', (function(poi) {
                    return function(e) {
                        openInfo(poi);
                    };
                })(poi));
            }

            if (clickBehaviour == 'POPUP') {
                marker.bindPopup(buildPopup(poi));
            }

            if (clickBehaviour == 'LINK') {
                marker.on('click', (function(poi) {
                    return function(e) {
                        window.location = (poi['slug']);
                    };
                })(poi));
            }

            marker.addTo(map);
            bounds.extend(poi['latlng']);
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

    let buildInfo = function(poi) {
        content = `${poi['image'] ? `<img src="${poi['image']}" width="100%" />` : 'Kein Bild'}
        <h3><a href="${poi['slug']}">${poi['title']}</a></h3>
        ${poi['subtitle'] ? `<h4>${poi['subtitle']}</h4>` : ''}
        ${poi['abstract'] ? `<p>${poi['abstract']}</p>` : ''}
        ${poi['address'] ? `<p>${nl2br(poi['address'])}</p>`: ''}
        <p><a href="${poi['slug']}">Mehr Informationen</a></p>`

        return content;
    }

    let buildPopup = function(poi) {
        content = `<p>
        <b><a href="${poi['slug']}">${poi['title']}</a></b>
        ${poi['image'] ? `<br><img src="${poi['image']}" width="120" />` : ''}
        ${poi['subtitle'] ? `<br><b>${poi['subtitle']}</b>` : ''}
        ${poi['abstract'] ? `<br><i>${poi['abstract']}</i>` : ''}
        ${poi['address'] ? `<br><small>${nl2br(poi['address'])}</small>`: ''}
        </p>`

        return content;
    }

    let openInfo = function(poi) {
        {   
            let infobox = document.getElementById("infobox");
    
            if (infobox.getAttribute("data-current-poi") == poi['uid']) {
                infobox.classList.remove("slide-in");
                infobox.classList.add("slide-out");
                infobox.removeAttribute("data-current-poi");
            } else {
                infobox.innerHTML = buildInfo(poi);
                infobox.classList.remove("slide-out");ç
                infobox.classList.add("slide-in");
                infobox.setAttribute("data-current-poi", poi['uid']);
            }
        }
    }

    if (document.readyState!='loading') initialize();
    else if (document.addEventListener) document.addEventListener('DOMContentLoaded', initialize);
})();

<style>
    #markerLayer img {
        border-radius: 50%;
      }

      #markerLayer img[src*='/men/'] { border: 2px solid #00adee !important; }
      #markerLayer img[src*='/women/'] { border: 2px solid #e254ac !important;}
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= $api_key ?>&v=<?= $api_ver ?>&callback=initMap" async defer></script>
<script type="text/javascript">
    var map;
    var points = <?= json_encode($users) ?>;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center           : new google.maps.LatLng(0, 0),

            zoom             : 1,
            minZoom          : 1,
            maxZoom          : 1,
            draggable        : false,

            disableDefaultUI : true,
            mapTypeId        : "terrain",
            styles           : [
                {elementType: "all", stylers:{visibility:"off"}},
                {elementType: "administrative.country", stylers:{visibility:"off",color:"#00adee"}},
                {featureType: "administrative", elementType: "geometry.fill", stylers: [{ visibility: "off" }]}
            ],
        });

        for (var i = points.length - 1; i >= 0; i--) {
            var icon  = {
                url        : points[i][2], // url
                scaledSize : new google.maps.Size(20, 20), // scaled size
                origin     : new google.maps.Point(0,0), // origin
                anchor     : new google.maps.Point(0, 0) // anchor
            };

            var marker = new google.maps.Marker({
                position  : new google.maps.LatLng(points[i][0], points[i][1]),
                icon      : icon,
                map       : map,
                optimized :false
            });
        }

        // I create an OverlayView, and set it to add the "markerLayer" class to the markerLayer DIV
        var myoverlay  = new google.maps.OverlayView();
        myoverlay.draw = function () {
            this.getPanes().markerLayer.id = 'markerLayer';
        };

        myoverlay.setMap(map);
    }
</script>

<div id="map" style="height:250px;width:100%"></div>

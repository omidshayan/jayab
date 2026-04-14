<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>jayab</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 98vh;
        }
    </style>
</head>

<body>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        var map = L.map('map').setView([34.352, 62.204], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);
    </script>

    <!-- change location -->
    <script>
        var clickMarker;

        map.on('click', function(e) {

            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            console.log("Clicked Location:", lat, lng);

            if (clickMarker) {
                map.removeLayer(clickMarker);
            }

            clickMarker = L.marker([lat, lng])
                .addTo(map)
                .bindPopup(
                    "📍 مکان انتخاب شده<br>" +
                    "Lat: " + lat.toFixed(6) + "<br>" +
                    "Lng: " + lng.toFixed(6)
                )
                .openPopup();

        });
    </script>

    <!-- get my location -->
    <script>
        var userMarker;

        if (navigator.geolocation) {

            navigator.geolocation.watchPosition(
                function(position) {

                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;

                    console.log("User Location:", lat, lng);

                    if (!userMarker) {

                        userMarker = L.marker([lat, lng])
                            .addTo(map)
                            .bindPopup("📍 موقعیت شما")
                            .openPopup();

                        // فقط بار اول زوم کن
                        map.setView([lat, lng], 15);

                    } else {

                        userMarker.setLatLng([lat, lng]);

                    }

                },
                function(error) {
                    console.log("GPS Error:", error.message);
                }, {
                    enableHighAccuracy: true,
                    maximumAge: 0,
                    timeout: 5000
                }
            );

        } else {
            alert("GPS پشتیبانی نمی‌شود");
        }
    </script>

</body>

</html>
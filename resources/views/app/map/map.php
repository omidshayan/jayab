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

    <!-- get location -->
    <script>
        var userMarker;

        // 🧭 بررسی پشتیبانی GPS
        if (navigator.geolocation) {

            navigator.geolocation.watchPosition(
                function(position) {

                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;

                    console.log("User Location:", lat, lng);

                    // اگر مارکر وجود ندارد → بساز
                    if (!userMarker) {

                        userMarker = L.marker([lat, lng])
                            .addTo(map)
                            .bindPopup("📍 موقعیت فعلی شما")
                            .openPopup();

                        // زوم روی کاربر
                        map.setView([lat, lng], 15);

                    } else {
                        // فقط آپدیت موقعیت
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
            alert("GPS در این مرورگر پشتیبانی نمی‌شود");
        }
    </script>
    
</body>

</html>
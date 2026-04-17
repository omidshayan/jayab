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

    <form action="<?= url('mapInfo/store') ?>" method="post">

        <input type="text" id="name" name="name" placeholder="نام مکان">

        <select name="type">
            <option value="street">خیابان</option>
            <option value="road">جاده</option>
            <option value="alley">کوچه</option>
            <option value="shop">فروشگاه</option>
        </select>

        <textarea id="description" name="description" placeholder="توضیحات"></textarea>

        <input type="text" name="points" id="points">

        <input type="text" id="lat" placeholder="lat" name="lat">
        <input type="text" id="lng" placeholder="lng" name="lng">
        <input type="text" name="street_name" placeholder="نام خیابان">
        <input type="submit" id="submit" value="ذخیره">
    </form>


    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([34.352, 62.204], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);
    </script>

    <!-- infos locations -->
    <script>
        var clickMarker;

        map.on('click', function(e) {

            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

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
    <style>
        .map-label {
            white-space: nowrap;
            font-size: 13px;
            font-weight: bold;

            background: rgba(255, 255, 255, 0.9);
            padding: 4px 10px;
            border-radius: 8px;

            border: 1px solid #ccc;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);

            transform: translate(-50%, -50%);
        }
    </style>
    <!-- get my location -->
    <script>
        var userMarker;

        if (navigator.geolocation) {

            navigator.geolocation.watchPosition(
                function(position) {

                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;

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

    <!-- get all locations -->
    <script>
fetch("<?= url('get-places') ?>")
.then(res => res.json())
.then(res => {

    if (!res.success || !res.data) return;

    res.data.forEach(place => {

        if (place.points) {

            let coords = JSON.parse(place.points);

            let line = L.polyline(coords, {
                color: '#4ece4a',
                weight: 4
            }).addTo(map);

            let center = line.getBounds().getCenter();

            L.marker(center, {
                icon: L.divIcon({
                    className: 'map-label',
                    html: place.name
                })
            }).addTo(map);
        }

    });

});
    </script>
    <script>
let tempPoints = [];
let tempLine = null;

map.on('click', function(e) {

    tempPoints.push([e.latlng.lat, e.latlng.lng]);

    if (tempLine) {
        map.removeLayer(tempLine);
    }

    tempLine = L.polyline(tempPoints, { color: 'red' }).addTo(map);

    document.getElementById("points").value =
        JSON.stringify(tempPoints);
});
    </script>


    <!-- get lat & lng for new name -->
    <script>
        var selectedPoint = null;

        map.on('click', function(e) {

            selectedPoint = {
                lat: e.latlng.lat,
                lng: e.latlng.lng
            };

            document.getElementById("lat").value = selectedPoint.lat;
            document.getElementById("lng").value = selectedPoint.lng;

            L.marker([selectedPoint.lat, selectedPoint.lng])
                .addTo(map)
                .bindPopup("مکان انتخاب شد")
                .openPopup();

        });
    </script>


</body>

</html>
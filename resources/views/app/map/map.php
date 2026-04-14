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
        let url = "<?= url('get-places') ?>";

        fetch(url)
            .then(res => res.json())
            .then(res => {

                console.log(res); // فقط همین

                if (!res.success || !res.data) return;

                res.data.forEach(place => {

                    let name = place.street_name ? place.street_name : place.name;

                    L.marker([place.lat, place.lng])
                        .addTo(map)
                        .bindPopup(name);

                });

            })
            .catch(err => console.log("FETCH ERROR:", err));
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
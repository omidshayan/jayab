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



        // ================================
        // 🔥 STATE مرکزی پروژه
        // ================================
        let state = {
            selectedPoint: null,
            tempPoints: [],
            tempLine: null,
            userMarker: null
        };



        // ================================
        // 🟡 CLICK HANDLER (فقط یکی!)
        // ================================
        map.on('click', function(e) {

            let lat = e.latlng.lat;
            let lng = e.latlng.lng;

            // 1. ثبت نقطه انتخاب شده
            state.selectedPoint = {
                lat,
                lng
            };

            document.getElementById("lat").value = lat;
            document.getElementById("lng").value = lng;



            // 2. نمایش marker انتخابی
            if (state.userMarker) {
                map.removeLayer(state.userMarker);
            }

            state.userMarker = L.marker([lat, lng])
                .addTo(map)
                .bindPopup("📍 نقطه انتخاب شد")
                .openPopup();



            // 3. رسم مسیر (polyline)
            state.tempPoints.push([lat, lng]);

            if (state.tempLine) {
                map.removeLayer(state.tempLine);
            }

            state.tempLine = L.polyline(state.tempPoints, {
                color: 'red',
                weight: 4
            }).addTo(map);



            // 4. ذخیره در input hidden
            document.getElementById("points").value =
                JSON.stringify(state.tempPoints);
        });



        // ================================
        // 📍 GPS کاربر
        // ================================
        if (navigator.geolocation) {

            navigator.geolocation.watchPosition(
                function(position) {

                    let lat = position.coords.latitude;
                    let lng = position.coords.longitude;

                    if (!state.userGpsMarker) {

                        state.userGpsMarker = L.marker([lat, lng])
                            .addTo(map)
                            .bindPopup("📍 موقعیت شما")
                            .openPopup();

                        map.setView([lat, lng], 15);

                    } else {

                        state.userGpsMarker.setLatLng([lat, lng]);
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
        }



        // ================================
        // 🟢 دریافت دیتا از سرور
        // ================================
        fetch("<?= url('get-places') ?>")
            .then(res => res.json())
            .then(res => {

                if (!res.success || !res.data) return;

                res.data.forEach(place => {

                    if (place.points) {

                        let coords = JSON.parse(place.points);

                        // رسم خط خیابان
                        let line = L.polyline(coords, {
                            color: '#4ece4a',
                            weight: 4
                        }).addTo(map);

                        // مرکز خط برای label
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


</body>

</html>
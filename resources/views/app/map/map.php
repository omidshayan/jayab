<!DOCTYPE html>
<html dir="rtl">

<head>
    <title>نقشه اختصاصی افغانستان - فاز ۱</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 600px;
            width: 100%;
            border-radius: 10px;
        }

        body {
            font-family: 'Tahoma', sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }

        .info {
            margin-bottom: 10px;
            padding: 10px;
            background: white;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <div class="info">
        <h2>پروژه نقشه افغانستان</h2>
        <p>در حال نمایش موقعیت فعلی شما و تست نام‌گذاری معابر...</p>
    </div>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://orthoquis.github.io/Leaflet.TextPath/leaflet.textpath.js"></script>

    <script>
        // ۱. تنظیم نقشه روی مرکز افغانستان (کابل) به صورت پیش‌فرض
        var map = L.map('map').setView([34.5553, 69.2075], 6);

        // ۲. لود تایل‌های نقشه
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // ۳. پیدا کردن موقعیت فعلی کاربر (User Location)
        map.locate({
            setView: true,
            maxZoom: 16
        });

        function onLocationFound(e) {
            var radius = e.accuracy / 2;
            L.marker(e.latlng).addTo(map)
                .bindPopup("شما در این محدوده هستید").openPopup();
            L.circle(e.latlng, radius).addTo(map);
        }

        map.on('locationfound', onLocationFound);

        // مدیریت خطا در صورت عدم دسترسی به مکان
        function onLocationFound(e) {
            // فقط اضافه کردن مارکر بدون ترسیم دایره (L.circle حذف شد)
            L.marker(e.latlng).addTo(map)
                .bindPopup("موقعیت فعلی شما").openPopup();
        }
        map.on('locationerror', onLocationError);

        // ۴. تست رسم یک خیابان (مثلاً در کابل) برای اطمینان از صحت کارکرد نام‌ها
        var testRoad = [
            [34.535, 69.150],
            [34.537, 69.165],
            [34.540, 69.180]
        ];

        var polyline = L.polyline(testRoad, {
            color: '#e74c3c',
            weight: 6
        }).addTo(map);
        polyline.setText('خیابان نمونه فاز ۱', {
            repeat: true,
            offset: -10,
            attributes: {
                'font-weight': 'bold',
                'fill': 'red'
            }
        });
    </script>
</body>

</html>
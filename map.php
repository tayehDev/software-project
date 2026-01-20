
<?php
include "header.php";
?>


<?php
$lat = isset($_GET['latitude']) ? floatval($_GET['latitude']) :''; // الخليل
$lng = isset($_GET['longitude']) ? floatval($_GET['longitude']) : '';
if(empty($lat)||empty($lng))
{
  die("<div align='middle'><h1>لم يتم ضبط احداثيات المشترك </h1></div>");
}
?>


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

<style>
    #map {
        width: 100%;
        height: 70vh;
        margin-top: 33px;
    }

</style>
<br><br><br><br>
<center>

    <div id="map"></div>
    <!--div id="info">جاري تحديد موقعك...</div-->

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <script>
        const destinationLat = <?= $lat ?>;
        const destinationLng = <?= $lng ?>;

        const map = L.map('map').setView([destinationLat, destinationLng], 24);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Marker للوجهة
        L.marker([destinationLat, destinationLng]).addTo(map).bindPopup("الوجهة").openPopup();

        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const currentLat = position.coords.latitude;
                    const currentLng = position.coords.longitude;

                    // Marker للموقع الحالي
                    L.marker([currentLat, currentLng]).addTo(map).bindPopup("موقعي الحالي").openPopup();

                    const control = L.Routing.control({
                        waypoints: [
                            L.latLng(currentLat, currentLng),
                            L.latLng(destinationLat, destinationLng)
                        ],
                        routeWhileDragging: false,
                        show: false,
                        addWaypoints: false,
                        draggableWaypoints: false
                    }).addTo(map);

                    control.on('routesfound', function(e) {
                        const route = e.routes[0];
                        const distance = route.summary.totalDistance; // بالأمتار
                        const time = route.summary.totalTime; // بالثواني

                        document.getElementById("info").innerHTML =
                            "المسافة: " + (distance / 1000).toFixed(2) + " كم<br>" +
                            "الوقت التقريبي: " + Math.round(time / 60) + " دقيقة";
                    });

                    control.on('routingerror', function() {
                        document.getElementById("info").innerText = "تعذر حساب المسار، تحقق من الاتصال بالإنترنت.";
                    });
                },
                function(error) {
                    let message = "";
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            message = "تم رفض الإذن للوصول إلى الموقع (يجب استخدام HTTPS).";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message = "معلومات الموقع غير متوفرة.";
                            break;
                        case error.TIMEOUT:
                            message = "انتهت مهلة تحديد الموقع.";
                            break;
                        default:
                            message = "حدث خطأ غير معروف أثناء تحديد الموقع.";
                    }
                    document.getElementById("info").innerText = message;
                }
            );
        } else {
            document.getElementById("info").innerText = "المتصفح لا يدعم تحديد الموقع.";
        }
    </script>


<?php
include "footer.php";
?>

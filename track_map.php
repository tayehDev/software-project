<?php
include "header.php";


// ===============================
// 1. Get order_id from URL
// ===============================
$order_id = intval($_GET['order_id']);

// ===============================
// 2. SQL Query to get buyer & seller locations
// ===============================
$sql = "
SELECT 
    o.id AS order_id,
    ub.latitude AS buyer_latitude,
    ub.longitude AS buyer_longitude,
    us.latitude AS seller_latitude,
    us.longitude AS seller_longitude
FROM orders_list o
LEFT JOIN products p ON p.id = o.product_id
LEFT JOIN users ub ON ub.id = o.by_user_id
LEFT JOIN users us ON us.id = p.by_user_id
WHERE o.id = $order_id
";
//echo "<br><br><br><br><br><br><br><br><br><br><br><br>".$sql;

$query = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($query);

// If order not found
if(!$row){
    echo "<h3 style='color:red;text-align:center;margin-top:100px'>Order not found</h3>";
    include "footer.php";
    exit;
}

// ===============================
// 3. Assign coordinates
// ===============================
$buyer_lat = $row['buyer_latitude'];
$buyer_lng = $row['buyer_longitude'];

$seller_lat = $row['seller_latitude'];
$seller_lng = $row['seller_longitude'];

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
<h2 style="margin-top:120px;">Route Tracking Between Buyer and Seller</h2>
<?php
if(empty($buyer_lat)||empty($buyer_lng)||empty($seller_lat)||empty($seller_lng))
{
    die("<br><br><span style='color:red'>Both the buyer and seller must set their location on the map in order to use this service</span>");
}

?>
<div id="map"></div>

<!-- JS Libraries -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

<script>
// ===============================
// 4. Send PHP coordinates to JS
// ===============================
const buyerLat = <?= $buyer_lat ?>;
const buyerLng = <?= $buyer_lng ?>;

const sellerLat = <?= $seller_lat ?>;
const sellerLng = <?= $seller_lng ?>;

// ===============================
// 5. Initialize Map
// ===============================
const map = L.map('map').setView(
    [(buyerLat + sellerLat) / 2, (buyerLng + sellerLng) / 2], 
    14
);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18
}).addTo(map);

// ===============================
// 6. Add markers
// ===============================
L.marker([buyerLat, buyerLng]).addTo(map).bindPopup("📍 Buyer Location");
L.marker([sellerLat, sellerLng]).addTo(map).bindPopup("📍 Seller Location");

// ===============================
// 7. Draw route between Buyer ↔ Seller
// ===============================
L.Routing.control({
    waypoints: [
        L.latLng(buyerLat, buyerLng),
        L.latLng(sellerLat, sellerLng)
    ],
    routeWhileDragging: false,
    draggableWaypoints: false,
    addWaypoints: false,
    show: false
}).addTo(map);

</script>

<?php
include "footer.php";
?>
</body>
</html>


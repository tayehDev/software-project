<?php
include "header.php";
?>

<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Ratings</h1>
</div>

<style>
    .star {
        font-size: 22px;
        cursor: pointer;
        color: grey;
        direction: ltr;
    }
    .star.checked {
        color: gold;
    }
</style>

<div class="container py-4">
    <?php
    $order_id = intval($_GET['order_id']);

    // جلب التقييمات فقط
    $sql = "
        SELECT 
            id AS order_id,
            evaluate_buyer_by_courier,
            evaluate_seller_by_buyer,
            evaluate_buyer_by_seller
        FROM orders_list
        WHERE id = $order_id
    ";

    $query = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($query);
    ?>

    <!-- تقييم الكوريير للمشتري -->
    <h5>Courier → Buyer</h5>
    <div>
        <?php for($i=1;$i<=5;$i++): ?>
            <span class="star <?php if($row['evaluate_buyer_by_courier'] >= $i) echo 'checked'; ?>"
                  onclick="set_evaluation(<?php echo $order_id; ?>, <?php echo $i; ?>, 'evaluate_buyer_by_courier');">
                ★
            </span>
        <?php endfor; ?>
    </div>
    <br>

    <!-- تقييم المشتري للبائع -->
    <h5>Buyer → Seller</h5>
    <div>
        <?php for($i=1;$i<=5;$i++): ?>
            <span class="star <?php if($row['evaluate_seller_by_buyer'] >= $i) echo 'checked'; ?>"
                  onclick="set_evaluation(<?php echo $order_id; ?>, <?php echo $i; ?>, 'evaluate_seller_by_buyer');">
                ★
            </span>
        <?php endfor; ?>
    </div>
    <br>

    <!-- تقييم البائع للمشتري -->
    <h5>Seller → Buyer</h5>
    <div>
        <?php for($i=1;$i<=5;$i++): ?>
            <span class="star <?php if($row['evaluate_buyer_by_seller'] >= $i) echo 'checked'; ?>"
                  onclick="set_evaluation(<?php echo $order_id; ?>, <?php echo $i; ?>, 'evaluate_buyer_by_seller');">
                ★
            </span>
        <?php endfor; ?>
    </div>
    <br><br>
</div>

<script>

function set_evaluation(order_id, evaluation, column) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "order_backDB.php?action=set_evaluation&order_id=" + order_id + "&evaluation=" + evaluation + "&column=" + column, true);
    xhttp.send();
    location.reload();
}
</script>

<?php include "footer.php"; ?>


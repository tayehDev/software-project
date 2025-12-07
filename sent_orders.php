<?php
include "header.php";
?>
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Outgoing Orders</h1>
</div>
<!-- Single Page Header End -->

<br><br>

<script>
function set_evaluation(element, order_id) {
    var pass_data = new FormData();
    pass_data.append('action', 'set_evaluation');
    pass_data.append('order_id', order_id);
    pass_data.append('evaluation', $(element).val());

    $.ajax({
        type: "POST",
        url: "order_backDB.php",
        data: pass_data,
        contentType: false,
        processData: false,
        success: function(response) {
            response = JSON.parse(response.trim());

            if (response.status == "ok") {
                location.reload();
            } else if (response.status == "error") {
                alert(response.contentMsg);
            }
        }
    });
}

function delete_order(element, order_id) {
    var pass_data = new FormData();
    pass_data.append('action', 'delete_order');
    pass_data.append('order_id', order_id);

    $.ajax({
        type: "POST",
        url: "order_backDB.php",
        data: pass_data,
        contentType: false,
        processData: false,
        success: function(response) {
            response = JSON.parse(response.trim());

            if (response.status == "ok") {
                location.reload();
            } else if (response.status == "error") {
                alert(response.contentMsg);
            }
        }
    });
}
</script>

<!-- Orders Page Start -->
<div class="container-fluid py-1">
    <div class="container py-1">
        <div class="table-responsive">
            <table class="table" dir="ltr">
                <thead>
                  <tr>
                    <th class="text-center" scope="col">#</th>
                    <th class="text-center" scope="col">Order Time</th>
                    <th scope="col">Product</th>
                    <th class="text-center" scope="col">Price</th>
                    <th class="text-center" scope="col">Response</th>
                    <th class="text-center" scope="col">Cancel Order</th>
                  </tr>
                </thead>
                <tbody>
                  <?php

                  $sql = "SELECT orders_list.id as order_id, orders_list.sent_datetime as order_sent_datetime, orders_list.client_response, products.*, CONCAT(fullname,' ',nickname) AS user_name FROM orders_list LEFT JOIN products ON products.id=orders_list.product_id LEFT JOIN users ON users.id=products.by_user_id WHERE 1 AND orders_list.is_canceled_row=0 AND orders_list.by_user_id=$_SESSION[user_id]";
                  

                  $query = mysqli_query($connect, $sql);
                  $row_No = 0;
                  while($row = mysqli_fetch_array($query)) {
                      $row_No += 1;
                  ?>
                    <tr>
                        <td class="text-center">
                            <p class="mb-0 mt-4"><?php echo $row_No; ?></p>
                        </td>
                        <td class="text-center">
                            <p class="mb-0 mt-4"><?php echo date("Y-m-d", strtotime($row['order_sent_datetime'])); ?><br><?php echo date("H:i", strtotime($row['order_sent_datetime'])); ?></p>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center">
                                <img src="uploads/<?php echo $row['img']; ?>" class="img-fluid mp-5 rounded" style="width: 80px; height: 80px;" alt="">
                                <p class="m-4 mt-4"><a href="product_details.php?product_id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></p>
                            </div>
                        </td>
                        <td class="text-center">
                            <p class="mb-0 mt-4"><?php echo $row['price']; ?> ₪</p>
                        </td>
                        <td class="text-center">
                            <p class="mb-0 mt-4"><?php echo array(0=>"Pending",1=>"Approved",2=>"Rejected")[intval($row['client_response'])]; ?></p>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-md rounded-circle bg-light border mt-4" onclick="delete_order(this, <?php echo $row['order_id']; ?>)">
                                <i class="fa fa-times text-danger"></i>
                            </button>
                        </td>
                    </tr>
                  <?php
                  }
                  ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Orders Page End -->

<?php
include "footer.php";
?>


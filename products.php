<?php
include "header.php";
?>
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Products</h1>
</div>
<!-- Single Page Header End -->

<br><br>

<script>
function do_fun(element, action, row_id) {
    if (!confirm("Are you sure you want to delete this record?")) {
        return;
    }

    var formData = new FormData();
    formData.append('action', action);
    formData.append('row_id', row_id);

    $.ajax({
        type: "POST",
        url: "product_backDB.php",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            response = JSON.parse(response.trim());
            if (response.status === "ok") {
                location.reload();
            } else if (response.status === "error") {
                alert(response.contentMsg);
            }
        }
    });
}
</script>

<!-- Products Page Start -->
<div class="container-fluid py-1">
    <div class="container py-1">
        <div class="table-responsive" dir="ltr">
            <div class="m-3">
                <a href="product_template.php" class="btn btn-primary border-2 border-secondary py-2 px-3 rounded-pill text-white">
                    <span>Add Product</span>
                </a>
            </div>
            <table class="table" dir="ltr">
                <thead>
                  <tr>
                    <th class="text-center" width=2% scope="col">#</th>
                    <th class="text-center"width=15% scope="col">Insert Time</th>
                    <th scope="col" width=25%>Product</th>
                    <th class="text-center" width=20% scope="col">AI analysis</th>
                    <th class="text-center" width=9% scope="col">Price</th>
                    <th class="text-center" width=2% scope="col">Edit</th>
                    <th class="text-center" width=2% scope="col">Delete</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if ($_SESSION['user_role_account'] == 1) {
                      $sql = "SELECT products.*, category.name as category_name, fullname,business_name, ai_analysis
                              FROM products 
                              LEFT JOIN category ON category.id=products.category_id 
                              LEFT JOIN users ON users.id=products.by_user_id 
                              WHERE products.is_canceled_row=0";
                  } else if ($_SESSION['user_role_account'] == 2) {
                      $sql = "SELECT products.*, category.name as category_name, fullname,business_name, ai_analysis
                              FROM products 
                              LEFT JOIN category ON category.id=products.category_id 
                              LEFT JOIN users ON users.id=products.by_user_id  
                              WHERE products.is_canceled_row=0 AND products.by_user_id={$_SESSION['user_id']}";
                  }

                  $query = mysqli_query($connect, $sql);
                  $row_No = 0;
                  while ($row = mysqli_fetch_array($query)) {
                      $row_No++;
                  ?>
                    <tr>
                        <td class="text-center"><p class="mb-0 mt-4"><?php echo $row_No; ?></p></td>
                        <td class="text-center">
                            <p><?php echo date("Y-m-d H:i", strtotime($row['publish_datetime'])); ?></p>
                            <?php if ($_SESSION['user_role_account'] == 1) { ?>
                                <p class="text-primary">
                                    <a href="show_profile.php?user_id=<?php echo $row['by_user_id']; ?>">
                                        <?php echo $row['fullname'];?><br><span style="color:black"><?php echo $row['business_name'];?></span>
                                    </a>
                                </p>
                            <?php } ?>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center">
                                <img src="uploads/<?php echo $row['img']; ?>" class="img-fluid mp-5 rounded" style="width:80px;height:80px;" alt="">
                                <p class="m-4 mt-4" style="font-weight:bold;"><?php echo $row['category_name']; ?></p>
                                <p class="m-4 mt-4" style="color:#333;font-weight:bold;"><?php echo $row['title']; ?></p>
                            </div>
                        </td>
                        <td class="text-center">
                          <div style="width:100;text-align:center;"><?php echo $row['ai_analysis']; ?></div>
                        </td>
                        <td class="text-center"><p class="mb-0 mt-4"><?php echo $row['price']; ?> ₪</p></td>
                        <td class="text-center">
                            <a href="product_template.php?product_id=<?php echo $row['id']; ?>">
                                <button class="btn btn-md rounded-circle bg-light border mt-4 p-1" style="width:39px;height:39px;">
                                    <i class="fa fa-edit text-primary"></i>
                                </button>
                            </a>
                        </td>
                        <td class="text-center">
                            <button onclick="do_fun(this,'delete',<?php echo $row['id']; ?>);" class="btn btn-md rounded-circle bg-light border mt-4 p-1" style="width:39px;height:39px;">
                                <i class="fa fa-times text-danger"></i>
                            </button>
                        </td>
                    </tr>
                  <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Products Page End -->

<?php
include "footer.php";
?>


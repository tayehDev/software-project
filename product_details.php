<?php
include "header.php";
?>

<?php
if(is_numeric($_GET['product_id']))
{
  $sql="SELECT products.*, category.name as category_name, CONCAT(fullname,' ',business_name) AS user_name 
        FROM products 
        LEFT JOIN category ON category.id = products.category_id 
        LEFT JOIN users ON users.id = products.by_user_id  
        WHERE 1 AND products.is_canceled_row = 0 AND products.id = $_GET[product_id]";
  
  $query = mysqli_query($connect, $sql);
  
  if($row = mysqli_fetch_array($query))
  {
    $product_id = $row['id'];
    $title = $row['title'];
    $description = $row['description'];
    $img = $row['img'];

    $category_name = $row['category_name'];
    $brand_copy = $row['brand_copy'];
    $price = $row['price'];
    $by_user_name = $row['user_name'];
    $by_user_id = $row['by_user_id'];
  }
}
?>

<script>
function send_order(element, product_id)
{
  var pass_data = new FormData();
  pass_data.append('action', 'send_order');
  pass_data.append('product_id', product_id);

  $.ajax({
    type: "POST",
    url: "order_backDB.php",
    data: pass_data,
    contentType: false,
    processData: false,
    success: function(response) {
      response = JSON.parse(response.trim());
      
      if(response.status == "ok") {
        $("#response-msg").css({color:"green"});
        $("#response-msg").text(response.contentMsg);  
        incrementTagValueFirebase('notifications/<?php echo $by_user_id;?>');
      }
      else if(response.status == "error") {
        $("#response-msg").css({color:"red"});
        $("#response-msg").text(response.contentMsg);  
      }
    }
  });
}
</script>

<!-- Page Header -->
<div class="container-fluid py-5">
    <h1 class="text-center text-white display-6"></h1>
</div>

<!-- Single Product Section -->
<div class="container-fluid py-5 mt-5">
    <div class="container py-5">
        <div class="row g-4 mb-5">
            <div class="col-lg-12 col-xl-12">
                <div class="row g-4" dir="ltr">
                    
                    <!-- Product Image -->
                    <div class="col-lg-6">
                        <div class="border rounded">
                            <a href="#">
                              <?php if(!empty($row['img'])) { ?>
                                <img src="uploads/<?php echo $img;?>" class="img-fluid rounded" style="min-height:400px;" alt="Image">
                              <?php } else { ?>
                                <img src="assets/img/empty.png" class="img-fluid rounded" style="min-height:400px;" alt="Image">
                              <?php } ?>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="col-lg-6">
                        <h4 class="fw-bold mb-3"><?php echo $title;?></h4>
                        <p class="mb-3">Category: <?php echo $category_name;?></p>
                        <h5 class="fw-bold mb-3"><?php echo $price;?>₪</h5>
                        
                        <hr><br>
                        
                        <p class="mb-4"><?php echo $description;?></p>

                        <hr>
                        Publisher: 
                        <a href="show_profile.php?user_id=<?php echo $by_user_id;?>"> <?php echo $by_user_name;?> </a>
                        <hr>
                        
                        <?php
                        if($_SESSION['user_role_account'] == 1 || $_SESSION['user_role_account'] == 2|| $_SESSION['user_role_account'] == 3)
                        {
                          if($by_user_id != $_SESSION['user_id'])
                          {
                        ?>
                            <a onclick="send_order(this, <?php echo $product_id;?>)" class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary">
                                <i class="fa fa-paper-plane me-2 text-primary"></i> Place Order
                            </a>
                            <br>
                            <div id="response-msg"></div>
                        <?php
                          } else {
                        ?>
                            <a href="product_template.php?product_id=<?php echo $product_id;?>">Edit your product here</a>
                        <?php
                          }
                        }
                        ?>
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div>   
</div>

<?php
include "footer.php";
?>


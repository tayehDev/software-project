<?php
include "header.php";
?>

<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Incoming Orders</h1>
</div>
<!-- Single Page Header End -->
<style>
    .star {
        font-size: 20px;
        cursor: pointer;
        color: grey;
        direction:ltr;
    }
    .star.checked{
        color: gold;
    }
</style>
<br><br>
<script>


function set_evaluation(order_id,evaluation)
{
  
  //var pass_data={};
	var pass_data = new FormData();
    pass_data.append('action','set_evaluation');
    pass_data.append('column','evaluate_buyer_by_seller');
	pass_data.append('order_id',order_id);
	pass_data.append('evaluation',evaluation);
	

    //alert(order_id);


	$.ajax({
		type:"POST",
		url:"order_backDB.php",
		data:pass_data,
		contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
    	processData: false, // NEEDED, DON'T OMIT THIS
		success: function(response) {

			//alert(response);
			response=JSON.parse(response.trim());
		
			if(response.status=="ok")
			{
				//alert(response.body);
				//$("#response-msg").css({color:"green",});
				//$("#response-msg").text(response.body);	
				location.reload();
			}
			else if(response.status=="error")
			{
				alert(response.contentMsg);
				//$("#response-msg").css({color:"red",});
				//$("#response-msg").text(response.body);	
			}

	 	}
	});
		
					


}

</script>
<script>
function send_response(element, response, order_id)
{
    var pass_data = new FormData();
    pass_data.append('action','send_response');
    pass_data.append('order_id',order_id);
    pass_data.append('response',response);

    $.ajax({
        type:"POST",
        url:"order_backDB.php",
        data:pass_data,
        contentType: false,
        processData: false,
        success: function(response) {
            response = JSON.parse(response.trim());
        
            if(response.status=="ok")
            {
                location.reload();
            }
            else if(response.status=="error")
            {
                alert(response.contentMsg);
            }
        }
    });
}
</script>

<!-- Cart Page Start -->
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
                    
                    <?php 
                    if($_SESSION['user_role_account']==1) {
                    ?>
                    <th class="text-center" scope="col">Seller</th>
                    <?php
                    }
                    ?>
                    <th class="text-center" scope="col">Buyer</th>
                    <th class="text-center" scope="col">Courier</th>
                    <th class="text-center" scope="col">Track Map</th>
                    <th class="text-center" scope="col">Track Stage</th>
                    
                    <th class="text-center" scope="col">Payment</th>
                    <th class="text-center" scope="col">Accept/Reject</th>
                    <th class="text-center" scope="col">Rating</th>
                    <th class="text-center" scope="col">Order Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if($_SESSION['user_role_account']==1)
                      $sql="SELECT orders_list.id as order_id,orders_list.payment_method,orders_list.evaluate_buyer_by_seller,orders_list.by_user_id as sent_by_user_id,orders_list.sent_datetime as order_sent_datetime,orders_list.client_response,products.*,concat(u1.fullname,' ',u1.business_name) as buyer_name,concat(u2.fullname,' ',u2.business_name) as seller_name, CONCAT(courier.fullname,' ',courier.business_name) AS courier_name FROM orders_list LEFT JOIN products ON products.id=orders_list.product_id LEFT JOIN users u1 ON u1.id=orders_list.by_user_id LEFT JOIN users as courier ON courier.id=orders_list.courier_id LEFT JOIN users u2 ON u2.id=products.by_user_id WHERE 1 AND orders_list.is_canceled_row=0";
                  else if($_SESSION['user_role_account']==2)
                      $sql="SELECT orders_list.id as order_id,orders_list.payment_method,orders_list.evaluate_buyer_by_seller,orders_list.by_user_id as sent_by_user_id,orders_list.sent_datetime as order_sent_datetime,orders_list.client_response,products.*,concat(u1.fullname,' ',u1.business_name) as buyer_name,concat(u2.fullname,' ',u2.business_name) as seller_name, CONCAT(courier.fullname,' ',courier.business_name) AS courier_name FROM orders_list LEFT JOIN products ON products.id=orders_list.product_id LEFT JOIN users u1 ON u1.id=orders_list.by_user_id LEFT JOIN users as courier ON courier.id=orders_list.courier_id LEFT JOIN users u2 ON u2.id=products.by_user_id WHERE 1 AND orders_list.is_canceled_row=0 AND products.by_user_id=$_SESSION[user_id]";
                    
                  //echo $sql;
                  $query = mysqli_query($connect, $sql);
                  $row_No=0;
                  while($row = mysqli_fetch_array($query))
                  {
                    $row_No+=1;
                  ?>
                    <tr>
                        <td class="text-center">
                            <p class="mb-0 mt-4"><?php echo $row_No;?></p>
                        </td>

                        <td class="text-center">
                            <p class="mb-0 mt-4" style="font-size:0.9em;">
                                <?php echo date("Y-m-d",strtotime($row['order_sent_datetime']));?><br>
                                <?php echo date("H:i",strtotime($row['order_sent_datetime']));?>
                            </p>
                        </td>

                        <td class="text-center">
                            <div class="d-flex align-items-center">
                                <img src="uploads/<?php echo $row['img'];?>" 
                                     class="img-fluid mp-5 rounded" 
                                     style="width: 80px; height: 80px;" alt="">
                                <p class="m-4 mt-4">
                                    <a href="product_details.php?product_id=<?php echo $row['id'];?>">
                                        <?php echo $row['title'];?>
                                    </a>
                                </p>
                            </div>
                        </td>

                        <td class="text-center">
                            <p class="mb-0 mt-4"><?php echo $row['price'];?> ₪</p>
                        </td>
                        <?php 
                        if($_SESSION['user_role_account']==1) {
                        ?>
                        <td class="text-center">
                            <p class="mb-0 mt-4">
                                <a href="show_profile.php?user_id=<?php echo $row['by_user_id'];?>">
                                    <?php echo $row['seller_name'];?>
                                </a>
                            </p>
                        </td>
                        <?php
                        }
                        ?>
                        <td class="text-center">
                            <p class="mb-0 mt-4">
                                <a href="show_profile.php?user_id=<?php echo $row['sent_by_user_id'];?>">
                                    <?php echo $row['buyer_name'];?>
                                </a>
                            </p>
                            
                          <div style="margin-top:18px;" >
                              <span class="star <?php if($row['evaluate_buyer_by_seller']>=1)echo "checked";?>" onclick="set_evaluation(<?php echo $row['order_id'];?>,1);" >★</span>
                              <span class="star <?php if($row['evaluate_buyer_by_seller']>=2)echo "checked";?>" onclick="set_evaluation(<?php echo $row['order_id'];?>,2);" >★</span>
                              <span class="star <?php if($row['evaluate_buyer_by_seller']>=3)echo "checked";?>" onclick="set_evaluation(<?php echo $row['order_id'];?>,3);" >★</span>
                              <span class="star <?php if($row['evaluate_buyer_by_seller']>=4)echo "checked";?>" onclick="set_evaluation(<?php echo $row['order_id'];?>,4);" >★</span>
                              <span class="star <?php if($row['evaluate_buyer_by_seller']>=5)echo "checked";?>" onclick="set_evaluation(<?php echo $row['order_id'];?>,5);" >★</span>
                          </div>
                          
                        </td>
                        <td class="text-center">
                            <p class="mb-0 mt-4">
                                <a href="show_profile.php?user_id=<?php echo $row['courier_id'];?>">
                                    <?php echo $row['courier_name'];?>
                                </a>
                            </p>
                        </td>
                        <td class="text-center">
                            <p class="mb-0 mt-4">
                                <a class="fa fa-map" href="track_map.php?order_id=<?php echo $row['order_id'];?>">
                                    
                                </a>
                            </p>
                        </td>
                        <td class="text-center">
                            <?php
                                // جلب جميع مراحل التتبع لهذا الطلب
                                $track_q = mysqli_query($connect, "
                                    SELECT stage, at_datetime 
                                    FROM order_tracking 
                                    WHERE order_id = ".$row['order_id']."
                                    ORDER BY id ASC
                                ");
                                
                                $stages = [
                                    0 => "Processing",
                                    1 => "To Sorting Center",
                                    2 => "At Sorting Center",
                                    3 => "To Buyer",
                                    4 => "Delivered"
                                ];

                                $track_list = [];
                                while($track_row = mysqli_fetch_assoc($track_q)) {
                                    $stage_name = $stages[$track_row['stage']] ?? "Unknown";
                                    $time = date("Y-m-d H:i", strtotime($track_row['at_datetime']));
                                    $track_list[] = "<span style='display:block; margin-bottom:2px;font-size:0.8em;border-bottom:1px solid #aaa;'>$stage_name <small>($time)</small></span>";
                                }

                                if(empty($track_list)) {
                                    echo "<span>Not started</span>";
                                } else {
                                    echo implode("", $track_list);
                                }
                            ?>
                        </td>
                        <td class="text-center">
                            <select class="form-select" style="width:133px;" disabled>
                                <option value="" >--select--</option>
                                <option value="1" <?php if($row['payment_method'] == 1) echo 'selected'; ?>>On Delivery</option>
                                <option value="2" <?php if($row['payment_method'] == 2) echo 'selected'; ?>>Visa Card</option>
                                
                            </select>
                        </td>
                        <td class="text-center">
                            <button onclick="send_response(this,1,<?php echo $row['order_id'];?>)" 
                                    class="btn btn-md  mt-4">
                                ✅
                            </button>

                            <button onclick="send_response(this,2,<?php echo $row['order_id'];?>)" 
                                    class="btn btn-md  mt-4">
                                ⛔
                            </button>
                        </td>
                        <td class="text-center">
                            <p class="mb-0 mt-4">
                                <a href="rating.php?order_id=<?php echo $row['order_id'];?>">
                                    Rating 
                                </a>
                            </p>
                        </td>
                        <td class="text-center">
                            <p class="mb-0 mt-4">
                                <?php 
                                echo array(
                                    0=>"Pending",
                                    1=>"Accepted",
                                    2=>"Rejected"
                                )[intval($row['client_response'])];
                                ?> 
                            </p>
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
<!-- Cart Page End -->

<?php
include "footer.php";
?>


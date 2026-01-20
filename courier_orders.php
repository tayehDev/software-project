<?php
include "header.php";
?>

<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Courier Orders</h1>
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
    pass_data.append('column','evaluate_buyer_by_courier');
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
function order_tracking(element, stage, order_id)
{
    var pass_data = new FormData();
    pass_data.append('action','order_tracking');
    pass_data.append('order_id',order_id);
    pass_data.append('stage',stage);

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
                    <th class="text-center" scope="col">Seller</th>
                    <th class="text-center" scope="col">Buyer</th>
                    <th class="text-center" scope="col">Payment</th>
                    <th class="text-center" scope="col">Rating</th>
                    <?php
                    if($_SESSION['user_role_account']==1)
                    {
                    ?>
                    <th class="text-center" scope="col">Courier</th>
                    <?php
                    }
                    ?>
                    <th class="text-center" scope="col">Track Map</th>
                    <th class="text-center" scope="col">Change Stage</th>
                    <th class="text-center" scope="col">Track Stage</th>

                  </tr>
                </thead>
                <tbody>
                
                  <?php
                  

                  
                if($_SESSION['user_role_account']==1)
                {
                    $sql = "
                        SELECT 
                            orders_list.id AS order_id,
                            orders_list.by_user_id AS set_by_user_id,
                            orders_list.sent_datetime AS order_sent_datetime,
                            orders_list.client_response,
                            orders_list.evaluate_buyer_by_courier,
                            products.*,
                            seller.id as seller_id,
                            orders_list.courier_id,
                            orders_list.payment_method,
                            buyer.id as buyer_id,
                            concat(seller.fullname,' ',seller.business_name) as seller_name,
                            concat(courier.fullname,' ',courier.business_name) as courier_name,
                            concat(buyer.fullname,' ',buyer.business_name) as buyer_name
                        FROM orders_list
                        LEFT JOIN products ON products.id = orders_list.product_id
                        LEFT JOIN users AS seller ON seller.id = products.by_user_id
                        LEFT JOIN seller_courier ON seller_courier.seller_id = seller.id
                        LEFT JOIN users AS courier ON courier.id = seller_courier.courier_id
                        LEFT JOIN users AS buyer ON buyer.id = orders_list.by_user_id
                        WHERE orders_list.is_canceled_row = 0
                        AND client_response=1
                    ";
                }
                else if($_SESSION['user_role_account']==4)
                {
                    $courier_id = $_SESSION['user_id'];
                    $sql = "
                        SELECT 
                            orders_list.id AS order_id,
                            orders_list.by_user_id AS set_by_user_id,
                            orders_list.sent_datetime AS order_sent_datetime,
                            orders_list.client_response,
                            orders_list.evaluate_buyer_by_courier,
                            products.*,
                            seller.id as seller_id,
                            orders_list.courier_id,
                            orders_list.payment_method,
                            buyer.id as buyer_id,
                            concat(seller.fullname,' ',seller.business_name) as seller_name,
                            concat(courier.fullname,' ',courier.business_name) as courier_name,
                            concat(buyer.fullname,' ',buyer.business_name) as buyer_name
                        FROM orders_list
                        LEFT JOIN products ON products.id = orders_list.product_id
                        LEFT JOIN users AS seller ON seller.id = products.by_user_id
                        LEFT JOIN seller_courier ON seller_courier.seller_id = seller.id
                        LEFT JOIN users AS courier ON courier.id = seller_courier.courier_id
                        LEFT JOIN users AS buyer ON buyer.id = orders_list.by_user_id
                        WHERE orders_list.is_canceled_row = 0
                        AND client_response=1
                        AND orders_list.courier_id = $courier_id
                    ";
                }
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

                        <td class="text-center">
                            <p class="mb-0 mt-4">
                                <a href="show_profile.php?user_id=<?php echo $row['seller_id'];?>">
                                    <?php echo $row['seller_name'];?>
                                </a>
                            </p>
                        </td>
                        <td class="text-center">
                            <p class="mb-0 mt-4">
                                <a href="show_profile.php?user_id=<?php echo $row['buyer_id'];?>">
                                    <?php echo $row['buyer_name'];?>
                                </a>
                            </p>
                            
                          <div style="margin-top:18px;" >
                              <span class="star <?php if($row['evaluate_buyer_by_courier']>=1)echo "checked";?>" onclick="set_evaluation(<?php echo $row['order_id'];?>,1);" >★</span>
                              <span class="star <?php if($row['evaluate_buyer_by_courier']>=2)echo "checked";?>" onclick="set_evaluation(<?php echo $row['order_id'];?>,2);" >★</span>
                              <span class="star <?php if($row['evaluate_buyer_by_courier']>=3)echo "checked";?>" onclick="set_evaluation(<?php echo $row['order_id'];?>,3);" >★</span>
                              <span class="star <?php if($row['evaluate_buyer_by_courier']>=4)echo "checked";?>" onclick="set_evaluation(<?php echo $row['order_id'];?>,4);" >★</span>
                              <span class="star <?php if($row['evaluate_buyer_by_courier']>=5)echo "checked";?>" onclick="set_evaluation(<?php echo $row['order_id'];?>,5);" >★</span>
                          </div>
                            
                        </td>
                        <?php
                        if($_SESSION['user_role_account']==1)
                        {
                        ?>
                        <td class="text-center">
                            <p class="mb-0 mt-4">
                                <a href="show_profile.php?user_id=<?php echo $row['courier_id'];?>">
                                    <?php echo $row['courier_name'];?>
                                </a>
                            </p>
                        </td>
                       <?php
                       }
                       ?>
                        <td class="text-center">
                            <select class="form-select" style="width:133px;" disabled >
                                <option value="" >--select--</option>
                                <option value="1" <?php if($row['payment_method'] == 1) echo 'selected'; ?>>On Delivery</option>
                                <option value="2" <?php if($row['payment_method'] == 2) echo 'selected'; ?>>Visa Card</option>
                                
                            </select>
                        </td>
                        <td class="text-center">
                            <p class="mb-0 mt-4">
                                <a class="fa fa-map" href="track_map.php?order_id=<?php echo $row['order_id'];?>">
                                    
                                </a>
                            </p>
                        </td>
                        
                        <td class="text-center">
                            <?php
                                // Get last stage for this order
                                $track_q = mysqli_query($connect, "
                                    SELECT stage 
                                    FROM order_tracking 
                                    WHERE order_id = ".$row['order_id']."
                                    ORDER BY id DESC 
                                    LIMIT 1
                                ");
                                $track_row = mysqli_fetch_assoc($track_q);
                                $current_stage = $track_row['stage'] ??-1;
                            ?>
                            
                            <select class="form-control" 
                                    onchange="order_tracking(this, this.value, <?php echo $row['order_id'];?>)" <?php if($_SESSION['user_role_account']==1)echo "disabled";?>>
                                <option value="-1" >Not started</option>
                                <option value="0" <?php if($current_stage==0) echo 'selected'; ?>>Processing</option>
                                <option value="1" <?php if($current_stage==1) echo 'selected'; ?>>To Sorting Center</option>
                                <option value="2" <?php if($current_stage==2) echo 'selected'; ?>>At Sorting Center</option>
                                <option value="3" <?php if($current_stage==3) echo 'selected'; ?>>To Buyer</option>
                                <option value="4" <?php if($current_stage==4) echo 'selected'; ?>>Delivered</option>
                            </select>
                        </td>

                        <td class="text-center">
                            <p class="mb-0 mt-4">
                                <a href="rating.php?order_id=<?php echo $row['order_id'];?>">
                                    Rating 
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
                                /*echo "
                                    SELECT stage, at_datetime 
                                    FROM order_tracking 
                                    WHERE order_id = ".$row['order_id']."
                                    ORDER BY id ASC
                                ";*/
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


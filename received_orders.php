<?php
include "header.php";
?>


<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">الطلبات الواردة</h1>

</div>
<!-- Single Page Header End -->

<br><br>


<script>


function send_response(element,response,order_id)
{
  
  //var pass_data={};
	var pass_data = new FormData();
  pass_data.append('action','send_response');
	pass_data.append('order_id',order_id);
	pass_data.append('response',response);
	

  //alert(row_id);


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
				//alert(response.contentMsg);
				//$("#response-msg").css({color:"green",});
				//$("#response-msg").text(response.contentMsg);	
				location.reload();
			}
			else if(response.status=="error")
			{
				alert(response.contentMsg);
				//$("#response-msg").css({color:"red",});
				//$("#response-msg").text(response.contentMsg);	
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
                    <th class="text-center" scope="col">وقت الطلب</th>
                    <th scope="col">المنتج</th>
                    <th class="text-center" scope="col">السعر</th>

                    <th class="text-center" scope="col">المرسل</th>
                    <th class="text-center" scope="col">قبول</th>
                    <th class="text-center" scope="col">رفض</th>
                    <th class="text-center" scope="col">حالة الطلب</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if($_SESSION['user_role_account']==1)$sql="SELECT orders_list.id as order_id,orders_list.by_user_id as set_by_user_id,orders_list.sent_datetime as order_sent_datetime,orders_list.client_response,products.*,concat(fullname,' ',nickname) AS user_name FROM orders_list LEFT JOIN products ON products.id=orders_list.product_id LEFT JOIN users ON users.id=orders_list.by_user_id WHERE 1 AND orders_list.is_canceled_row=0";
                  else if($_SESSION['user_role_account']==2)$sql="SELECT orders_list.id as order_id,orders_list.by_user_id as set_by_user_id,orders_list.sent_datetime as order_sent_datetime,orders_list.client_response,products.*,concat(fullname,' ',nickname) AS user_name FROM orders_list LEFT JOIN products ON products.id=orders_list.product_id LEFT JOIN users ON users.id=orders_list.by_user_id  WHERE 1 AND orders_list.is_canceled_row=0 AND products.by_user_id=$_SESSION[user_id]";
                  
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
                            
                            <p class="mb-0 mt-4 "><?php echo date("Y-m-d",strtotime($row['order_sent_datetime']));?><br><?php echo date("H:i",strtotime($row['order_sent_datetime']));?></p>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center">
                                <img src="uploads/<?php echo $row['img'];?>" class="img-fluid mp-5 rounded" style="width: 80px; height: 80px;" alt="" alt="">
                                <p class="m-4 mt-4"><a href="product_details.php?product_id=<?php echo $row['id'];?>"><?php echo $row['title'];?></a></p>
                            </div>
                        </td>
                        <td class="text-center">
                            
                            <p class="mb-0 mt-4"><?php echo $row['price'];?> ₪</p>
                        </td>
                        
                        <td class="text-center">
                            <p class="mb-0 mt-4"><a href="show_profile.php?user_id=<?php echo $row['set_by_user_id'];?>"><?php echo $row['user_name'];?></a></p>
                        </td>
                        <td class="text-center">
                            <button onclick="send_response(this,1,<?php echo $row['order_id'];?>)" class="btn btn-md rounded-circle bg-light border mt-4" >
                                <i class="fa fa-check text-success"></i>
                            </button>
                        </td>
                        <td class="text-center">
                            <button onclick="send_response(this,2,<?php echo $row['order_id'];?>)" class="btn btn-md rounded-circle bg-light border mt-4" >
                                <i class="fa fa-times text-danger"></i>
                            </button>
                        </td>
                        
                        <td class="text-center">
                            
                            <p class="mb-0 mt-4"><?php echo array(0=>"انتظار",1=>"قمت بالموافقة",2=>"قمت بالرفض")[intval($row['client_response'])];?> </p>
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

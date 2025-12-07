<?php
include "header.php";
?>

<script>
function signup(element)
{
	var fullname = $("[name='fullname']").val();
  	var nickname = $("[name='nickname']").val();
  	var address = $("[name='address']").val();
  	var phone = $("[name='phone']").val();

  	if(phone==0 || phone=='' || phone===undefined || phone.length<9)
	{
		alert('Please enter a valid phone number');
		return;
	}
	
	var area_id = $("[name='area_id'] option:selected").val();

	if(fullname=='' || nickname=='' || fullname.length<3 || nickname.length<3)
	{
		alert('Please enter valid first and last name');
		return;
	}

	if(area_id==0 || area_id=='' || area_id===undefined)
	{
		alert('Please select a residence');
		return;
	}

	var password = $("[name='password']").val();
	var confirm_password = $("[name='confirm_password']").val();
	
	if(password!=confirm_password)
	{
	  alert("Passwords do not match");
	  return;
	}
	
	var pass_data = new FormData();

	pass_data.append('fullname',fullname);
  	pass_data.append('nickname',nickname);
  	pass_data.append('address',address);
  	pass_data.append('phone',phone);
  	pass_data.append('img',$("[name='img']")[0].files[0]);
	pass_data.append('area_id',area_id);
	pass_data.append('password',password);

	$.ajax({
		type:"POST",
		url:"signup_backDB.php",
		data:pass_data,
		contentType: false,
    	processData: false,
		success: function(response) {
			response = JSON.parse(response.trim());
		
			if(response.status=="ok")
			{
				$("#response-msg").css({color:"green"});
				$("#response-msg").text(response.contentMsg);	
				setTimeout(function(){ 
					location.reload();
				}, 2000);
			}
			else if(response.status=="error")
			{
				$("#response-msg").css({color:"red"});
				$("#response-msg").text(response.contentMsg);	
			}
	 	}
	});
}
</script>


<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Create a Free Account</h1>
</div>
<!-- Single Page Header End -->

<br>

<!-- Signup Page Start -->
<div class="container-fluid">
    <div class="container" dir="ltr">
        <form action="#">
            <div class="row g-5">
                <div class="col-md-12 col-lg-6 col-xl-7">

                    <h5 class="mb-4">Create a New Account</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <div class="form-item w-100">
                                <label class="form-label my-3">First Name<sup>*</sup></label>
                                <input type="text" name="fullname" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <div class="form-item w-100">
                                <label class="form-label my-3">Last Name<sup>*</sup></label>
                                <input type="text" name="nickname" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="form-item w-100">
                            <div class="form-item w-100">
                                <label class="form-label my-3">Profile Picture</label>
                                <input type="file" name="img" class="form-control"> 
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-item">
                        <label class="form-label my-3">City<sup>*</sup></label>
                        <select class="form-control" name="area_id" id="area_id">
                          <option></option>
                          <?php
                          $query_area = mysqli_query($connect, "SELECT * FROM area ORDER BY id DESC ");
                          while ($row_area = mysqli_fetch_array($query_area)) {
                          ?>
                          <option value="<?php echo $row_area['id'];?>" <?php if($area_id==$row_area['id'])echo "selected";?>><?php echo $row_area['name'];?></option>
                          <?php } ?>
                        </select>   
                    </div>
                    
                    <div class="form-item">
                        <label class="form-label my-3">Address</label>
                        <input type="text" class="form-control" name="address" placeholder="Village / Street / Building">
                    </div>
                    
                    <div class="form-item">
                        <label class="form-label my-3">Phone<sup>*</sup></label>
                        <input type="tel" name="phone" class="form-control">
                    </div>
                    
                    <div class="form-item">
                        <label class="form-label my-3">Password<sup>*</sup></label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    
                    <div class="form-item">
                        <label class="form-label my-3">Confirm Password<sup>*</sup></label>
                        <input type="password" name="confirm_password" class="form-control">
                    </div>
                    
                    <br>
                    
                    <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                        <button type="button" onclick="signup(this)" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Sign Up</button>
                    </div>
                    <br>
                    <div id="response-msg"></div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Signup Page End -->

<?php
include "footer.php";
?>


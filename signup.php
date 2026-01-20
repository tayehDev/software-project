<?php
include "header.php";
?>
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Create a Free Account</h1>
</div>
<!-- Single Page Header End -->

<br>
<style>

body {
    background: #f4f7fc;
    font-family: 'Poppins', sans-serif;
}

/* صندوق التسجيل */
.signup-box {
    background: #ffffff;
    border-radius: 18px;
    padding: 35px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.12);
    transition: 0.3s;
}

.signup-box:hover {
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}


/* الأزرار */
.btn-primary-custom {
    background: linear-gradient(135deg, #007bff, #0056d6);
    color: #fff !important;
    border-radius: 10px;
    border: none;
    font-size: 17px;
    padding: 14px;
    letter-spacing: 1px;
    transition: 0.3s;
}

.btn-primary-custom:hover {
    background: linear-gradient(135deg, #005ce6, #003bb3);
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0, 94, 255, 0.35);
}

/* حقول الإدخال */
.form-control {
    height: 52px;
    border-radius: 10px;
    border: 1px solid #d8d8d8;
    transition: 0.2s;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.15);
}

/* عناوين الحقول */
.form-label {
    font-weight: 600;
    color: #333;
}

/* رابط تسجيل الدخول */
.login-link {
    font-size: 16px;
    color: #0056d6;
}

.login-link:hover {
    text-decoration: underline;
}

/* رسالة التنبيه */
#response-msg {
    font-size: 17px;
    font-weight: bold;
    padding-top: 10px;
}

</style>



<script>
function signup(element)
{
	var fullname = $("[name='fullname']").val();
  	var business_name = $("[name='business_name']").val();
  	var address = $("[name='address']").val();
  	var phone = $("[name='phone']").val();

  	if(phone==0 || phone=='' || phone===undefined || phone.length<9)
	{
		alert('Please enter a valid phone number');
		return;
	}
	
	var area_id = $("[name='area_id'] option:selected").val();

	if(fullname=='' || business_name=='' || fullname.length<3 || business_name.length<3)
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
  	pass_data.append('business_name',business_name);
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




<!-- Signup Page Start -->
<div class="container-fluid mt-5 mb-5">
    <div class="container signup-box" dir="ltr">

        <form action="#">
            <h5 class="mb-4">Create a New Account</h5>
            <hr>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label my-2">Fullname<sup>*</sup></label>
                    <input type="text" name="fullname" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label my-2">Business Name<sup>*</sup></label>
                    <input type="text" name="business_name" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label my-2">Profile Picture</label>
                    <input type="file" name="img" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label my-2">City<sup>*</sup></label>
                    <select class="form-control" name="area_id" id="area_id">
                        <option value="">Select City</option>
                        <?php
                        $query_area = mysqli_query($connect, "SELECT * FROM area ORDER BY id DESC ");
                        while ($row_area = mysqli_fetch_array($query_area)) {
                        ?>
                        <option value="<?php echo $row_area['id']; ?>">
                            <?php echo $row_area['name']; ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label my-2">Address</label>
                    <input type="text" class="form-control" name="address" placeholder="Village / Street / Building">
                </div>

                <div class="col-md-6">
                    <label class="form-label my-2">Phone<sup>*</sup></label>
                    <input type="tel" name="phone" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label my-2">Password<sup>*</sup></label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label my-2">Confirm Password<sup>*</sup></label>
                    <input type="password" name="confirm_password" class="form-control">
                </div>

                <div class="col-12 text-center pt-3">
                    <button type="button" onclick="signup(this)" class="btn btn-primary-custom w-100">
                        Sign Up
                    </button>
                </div>

                <div class="col-12 text-center mt-3">
                    <a href="signin.php" class="login-link">Already have an account? Login</a>
                </div>

                <div id="response-msg" class="text-center mt-2"></div>

            </div>
        </form>

    </div>
</div>
<!-- Signup Page End -->


<?php
include "footer.php";
?>


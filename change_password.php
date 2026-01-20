<?php
include "header.php";
?>


<script>

function change_password(element)
{


  var phone=$("[name='phone']").val();

	if(phone==0||phone==''||phone===undefined || phone.length<9)
	{
		alert('Please enter a valid phone number');
		return;
	}


	
  //var pass_data={};
	var pass_data = new FormData();


  pass_data.append('phone',phone);

	

	$.ajax({
		type:"POST",
		url:"change_password_backDB.php",
		data:pass_data,
		contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
    	processData: false, // NEEDED, DON'T OMIT THIS
		success: function(response) {

			response=JSON.parse(response.trim());
		
			if(response.status=="ok")
			{
				$("#response-msg").css({color:"green"});
				$("#response-msg").text("Login successful");	
				setTimeout(function(){ 
					location.href="index.php";
				}, 1000);
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
    <h1 class="text-center text-white display-6">Welcome</h1>
</div>
<!-- Single Page Header End -->

<br>


<!-- Contact Start -->
<div class="container-fluid contact ">
    <div class="container ">
        <div class="p-1 rounded">
            <div class="row g-4" dir="ltr">
                <div class="col-lg-7">
 
                    <h5>Forget Password</h5>
                    <hr>

                        <input type="text" name="phone" class="w-100 form-control border-0 py-3 mb-4" placeholder="Your Phone">

                        <br>
                        
                        <button onclick="change_password(this);" class="w-100 btn form-control border-secondary py-3 bg-white text-primary " type="submit">Send Request To Change Paswword</button>
                        <br><br>
                        <a href="signin.php">Login Page</a>
                        <br><br>
                        <div id="response-msg"></div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact End -->

<?php
include "footer.php";
?>


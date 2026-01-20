<?php
include "header.php";

if($_SESSION['user_role_account'] != 1 && $_SESSION['user_role_account'] != 2)
{
	if(file_exists('index.php')) echo "<script> window.top.location.href = 'index.php';</script>";
	else if(file_exists('../index.php')) echo "<script> window.top.location.href = '../index.php';</script>";
	exit;
}
?>
<!-- Single Page Header start -->
<div class="container-fluid py-5"></div>
<!-- Single Page Header End -->

<!-- About Us Page Start -->
<div class="container-fluid py-5">
    <div class="container py-5" dir="ltr">

<script>
function run_controller(element, action)
{
	var question = $("[name='question']").val();
	var answer = $("[id='answer']").val();
	
	//alert(answer);

	var pass_data = new FormData();
	pass_data.append('action', action);
	pass_data.append('question', question);
	pass_data.append('answer', answer);

	if(action == 'edit_row')
	{
		var row_id = $("#row_id").val();
		if(row_id == 0 || row_id == '' || row_id === undefined)
		{
			alert('Error: Invalid record ID');
			return;
		}
		pass_data.append('row_id', row_id);
	}

	$.ajax({
		type: "POST",
		url: "chatAITrain_fun.php",
		data: pass_data,
		contentType: false,
		processData: false,
		success: function(response) {
			response = JSON.parse(response.trim());
			if(response.status == "ok")
			{
				$("#response-msg").css({color:"green"});
				$("#response-msg").text(response.contentMsg);	
				setTimeout(function(){ 
					if(action == 'add_row') location.href="chatAITrain.php";
					else location.reload(); 
				}, 2000);
			}
			else if(response.status == "error")
			{
				$("#response-msg").css({color:"red"});
				$("#response-msg").text(response.contentMsg);	
			}
	 	}
	});
}
</script>

<?php
$action_function = "add_row";

if ($_GET['action'] == 'edit_row' || $_GET['action'] == 'view_data') 
{
	if (isset($_SERVER['HTTP_REFERER'])) 
	{
		$row_id = intval($_GET['row_id']);
		$sql = "SELECT * FROM chat_conjunction WHERE chat_conjunction.id='$row_id'";
		$query = mysqli_query($connect, $sql);
		$row = mysqli_fetch_array($query);

		$question = htmlspecialchars($row['question']);
		$answer = htmlspecialchars($row['answer']);


	} 
	else {
		die("Error: Invalid request");
	}
}

if ($_GET['action'] == 'edit_row') 
{
	$action_function = "edit_row";
}
?>

<table class="table table-bordered" dir="ltr" style="font-size:0.95em; width:50%;">
  <tr>
      <td width="25%">Question</td>
      <td>
        <input type="text" name="question" class="form-control" required="required" value='<?php echo isset($question) ? $question : ''; ?>' />
      </td>
  </tr>
  
  <tr>
    <td>answer</td>
    <td>
        <textarea id="answer" class="form-control" rows="4" placeholder="Write answer here..."><?php echo isset($answer) ? $answer : ''; ?></textarea>
    </td>
  </tr>

  <tr class="control_showing">
    <?php 
    if($action_function == "edit_row") {
    ?>
    	<input type='hidden' id='row_id' value='<?php echo $row_id;?>' />
    	<td colspan=2>
    		<button class="btn btn-success" type='button' onclick="run_controller(this,'edit_row');">Save</button>
    	</td>
    <?php
    } else if($action_function == "add_row") {
    ?>
    	<td colspan=2>
    		<button class="btn btn-success" type='button' onclick="run_controller(this,'add_row');">Add</button>
    	</td>
    <?php
    }
    ?>
  </tr>

  <tr>
    <td colspan=2 align="center">
      <div id="response-msg"></div>
    </td>
  </tr>
</table>

    </div>
</div>

<?php
include "footer.php";
?>


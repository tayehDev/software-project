<?php
include "header.php";

if($_SESSION['user_role_account']!=1) {
    if(file_exists('../index.php')) echo "<script> window.top.location.href = '../index.php';</script>";
    else if(file_exists('../../index.php')) echo "<script> window.top.location.href = '../../index.php';</script>";
    exit;
}
?>
<!-- Single Page Header start -->
<div class="container-fluid py-5" style="background-color:#f7f9f7;"></div>
<!-- Single Page Header End -->

<!-- Head Image Management Page Start -->
<div class="container-fluid py-5" style="background-color:#f0f8e8;">
    <div class="container py-5" dir="ltr">

<script>
function run_controller(element, action) {
    var topic = $("[name='topic']").val();
    var img_file = $("[name='img']")[0].files[0];

    var pass_data = new FormData();
    pass_data.append('action', action);
    pass_data.append('topic', topic);
    if(img_file) pass_data.append('img', img_file);

    if(action == 'edit_row') {
        var row_id = $("#row_id").val();
        if(!row_id || row_id === '0') {
            alert('Error: Invalid record ID');
            return;
        }
        pass_data.append('row_id', row_id);
    }

    $.ajax({
        type: "POST",
        url: "head_img_fun.php",
        data: pass_data,
        contentType: false,
        processData: false,
        success: function(response) {
            response = JSON.parse(response.trim());
            if(response.status == "ok") {
                $("#response-msg").css({color:"green"});
                $("#response-msg").text(response.contentMsg);    
                setTimeout(function(){ 
                    if(action=='add_row') window.history.back();
                    else location.reload(); 
                }, 2000);
            } else if(response.status == "error") {
                $("#response-msg").css({color:"red"});
                $("#response-msg").text(response.contentMsg);    
            }
        }
    });
}
</script>

<?php
$action_function = "add_row";
$element_image = '<img src="assets/img/empty.png" style="max-width:100px;max-height:100px;"/>';

if(isset($_GET['action']) && ($_GET['action']=='edit_row' || $_GET['action']=='view_data')) {
    if(isset($_SERVER['HTTP_REFERER'])) {
        $row_id = intval($_GET['row_id']);
        $sql = "SELECT * FROM head_img WHERE id='$row_id'";
        $query = mysqli_query($connect, $sql);
        $row = mysqli_fetch_array($query);

        $topic = htmlspecialchars($row['topic']);
        $img = htmlspecialchars($row['img']);
        $src = "uploads/$img";
        if(!file_exists($src) || is_dir($src)) {
            $src = 'assets/img/empty.png';
        }
        $element_image = '<img src="'.$src.'" style="max-width:100px;max-height:100px; border-radius:5px;" />';
    } else {
        die("Error: Invalid request");
    }
}

if(isset($_GET['action']) && $_GET['action']=='edit_row') {
    $action_function = "edit_row";
}
?>

<table class="table table-bordered table-hover" dir="ltr" style="font-size:0.95em; width:50%; background:#fff; border-radius:8px; overflow:hidden;">
  <tr>
      <td width="25%" style="font-weight:bold;">Title</td>
      <td><input type="text" name="topic" class="form-control" required="required" value="<?php echo isset($topic) ? $topic : ''; ?>" /></td>
  </tr>
  
  <tr>
      <td style="font-weight:bold;">Image</td>
      <td>
          <input type="file" name="img" class="form-control-file mb-2"/>
          <?php echo $element_image; ?>
          <input type="hidden" name="oldimg" value="<?php echo isset($img) ? $img : ''; ?>" />
      </td>
  </tr>

  <tr class="control_showing">
      <td colspan="2" class="text-center">
          <?php if($action_function=="edit_row") { ?>
              <input type="hidden" id="row_id" value="<?php echo $row_id; ?>" />
              <button class="btn" style="background:#c3e88d;color:#fff;font-weight:bold;padding:8px 20px;border-radius:6px;" 
                      onclick="run_controller(this,'edit_row');">Save</button>
          <?php } else { ?>
              <button class="btn" style="background:#c3e88d;color:#fff;font-weight:bold;padding:8px 20px;border-radius:6px;" 
                      onclick="run_controller(this,'add_row');">Add</button>
          <?php } ?>
      </td>
  </tr>

  <tr>
      <td colspan="2" class="text-center">
          <div id="response-msg"></div>
      </td>
  </tr>
</table>

    </div>
</div>

<?php
include "footer.php";
?>


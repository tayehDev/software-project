<?php
include "header.php";

if ($_SESSION['user_role_account'] != 1) {
    if (file_exists('../index.php')) echo "<script>window.top.location.href = '../index.php';</script>";
    else if (file_exists('../../index.php')) echo "<script>window.top.location.href = '../../index.php';</script>";
    exit;
}
?>
<!-- Page Style -->
<style>
  body {
    background-color: #f8f9fa;
  }

  .form-container {
    background: #fff;
    border-radius: 15px;
    padding: 40px 50px;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
    transition: 0.3s;
  }

  .form-container:hover {
    box-shadow: 0 6px 30px rgba(0, 0, 0, 0.12);
  }

  .form-container h3 {
    color: #4a4a4a;
    font-weight: 600;
    text-align: center;
    margin-bottom: 30px;
  }

  .table td {
    padding: 12px;
    vertical-align: middle;
  }

  .form-control {
    border-radius: 10px;
    border: 1px solid #ccc;
    padding: 10px 14px;
    transition: 0.3s;
  }

  .form-control:focus {
    border-color: #81c408;
    box-shadow: 0 0 6px rgba(129, 196, 8, 0.4);
  }

  .btn-success {
    background-color: #81c408 !important;
    border: none;
    padding: 10px 25px;
    font-weight: 600;
    border-radius: 25px;
    transition: 0.3s;
  }

  .btn-success:hover {
    background-color: #6da107 !important;
    transform: translateY(-2px);
  }

  #response-msg {
    font-weight: bold;
    margin-top: 10px;
  }

  hr {
    border-top: 1px solid #ddd;
  }
</style>
<br><br>
<!-- Header -->
<div class="container-fluid py-5"></div>

<!-- Form Section -->
<div class="container py-5" dir="ltr">
  <div class="form-container">

    <h3>
      <?php echo ($_GET['action'] == 'edit_row') ? "Edit User Information" : "Add New User"; ?>
    </h3>

<script>
function run_controller(element, action) {
  var fullname = $("[name='fullname']").val();
  var nickname = $("[name='nickname']").val();
  var address = $("[name='address']").val();
  var phone = $("[name='phone']").val();
  var user_role_account = $("[name='user_role_account']").val();
  var area_id = $("[name='area_id'] option:selected").val();

  if (fullname == '' || nickname == '' || fullname.length < 3 || nickname.length < 3) {
    alert('Please enter valid first and last names.');
    return;
  }

  if (area_id == 0 || area_id == '' || area_id === undefined) {
    alert('Please select the residence area.');
    return;
  }

  var password = $("#password").val();

  var pass_data = new FormData();
  pass_data.append('action', action);
  pass_data.append('fullname', fullname);
  pass_data.append('nickname', nickname);
  pass_data.append('address', address);
  pass_data.append('phone', phone);
  pass_data.append('img', $("[name='img']")[0].files[0]);
  pass_data.append('user_role_account', user_role_account);
  pass_data.append('area_id', area_id);
  pass_data.append('password', password);

  if (action == 'edit_row') {
    var row_id = $("#row_id").val();
    if (row_id == 0 || row_id == '' || row_id === undefined) {
      alert('Error: missing record ID.');
      return;
    }
    pass_data.append('row_id', row_id);
  }

  $.ajax({
    type: "POST",
    url: "users_fun.php",
    data: pass_data,
    contentType: false,
    processData: false,
    success: function (response) {
      response = JSON.parse(response.trim());

      if (response.status == "ok") {
        $("#response-msg").css({ color: "#6da107" });
        $("#response-msg").text(response.contentMsg);
        setTimeout(function () {
          if (action == 'add_row') window.history.back();
          else location.reload();
        }, 2000);
      } else if (response.status == "error") {
        $("#response-msg").css({ color: "red" });
        $("#response-msg").text(response.contentMsg);
      }
    }
  });
}
</script>

<?php
$action_function = "add_row";
if ($_GET['action'] == 'edit_row' || $_GET['action'] == 'view_data') {
  if (isset($_SERVER['HTTP_REFERER'])) {
    $row_id = intval($_GET['row_id']);
    $sql = "SELECT * FROM users WHERE users.id='$row_id'";
    $query = mysqli_query($connect, $sql);
    $row = mysqli_fetch_array($query);

    $fullname = htmlspecialchars($row['fullname']);
    $area_id = $row['area_id'];
    $user_role_account = $row['user_role_account'];
    $nickname = $row['nickname'];
    $address = $row['address'];
    $phone = $row['phone'];
    $img = htmlspecialchars($row['img']);
    $src = "uploads/$img";
    if (!file_exists($src) || is_dir($src)) {
      $src = 'assets/img/empty.png';
    }
    $element_image = '<img src="' . $src . '" style="max-width:100px;max-height:100px;border-radius:8px;"/>';
  } else {
    die("Invalid request.");
  }
}

if ($_GET['action'] == 'edit_row') {
  $action_function = "edit_row";
}
?>

<table class="table" style="font-size:1em;width:100%;">
  <tr>
    <td width="25%">Account Type</td>
    <td>
      <select class="form-control" name="user_role_account" required="required">
        <option value="1" <?php if ($user_role_account == 1) echo "selected"; ?>>Admin</option>
        <option value="2" <?php if ($user_role_account == 2) echo "selected"; ?>>Seller</option>
        <option value="3" <?php if ($user_role_account == 3) echo "selected"; ?>>Buyer</option>
      </select>
    </td>
  </tr>

  <tr>
    <td>First Name</td>
    <td><input type="text" name="fullname" class="form-control" required value="<?php echo $fullname; ?>" /></td>
  </tr>

  <tr>
    <td>Last Name</td>
    <td><input type="text" name="nickname" class="form-control" required value="<?php echo $nickname; ?>" /></td>
  </tr>

  <tr>
    <td>Phone</td>
    <td><input type="text" name="phone" class="form-control" required value="<?php echo $phone; ?>" /></td>
  </tr>

  <tr>
    <td>Profile Image</td>
    <td>
      <input type="file" name="img" class="form-control"/>
      <div class="mt-2"><?php echo $element_image; ?></div>
      <input type="hidden" name="oldimg" value="<?php echo $img ?>" />
    </td>
  </tr>

  <tr>
    <td>Residence Area</td>
    <td>
      <select class="form-control" name="area_id" id="area_id">
        <option value="">Select area</option>
        <?php
        $query_area = mysqli_query($connect, "SELECT * FROM area where is_canceled_row=0 ORDER BY id DESC ");
        while ($row_area = mysqli_fetch_array($query_area)) {
        ?>
          <option value="<?php echo $row_area['id']; ?>" <?php if ($area_id == $row_area['id']) echo "selected"; ?>>
            <?php echo $row_area['name']; ?>
          </option>
        <?php } ?>
      </select>
    </td>
  </tr>

  <tr>
    <td>Address</td>
    <td><input type="text" name="address" class="form-control" value="<?php echo $address; ?>" /></td>
  </tr>



  <tr>
    <td>Password</td>
    <td><input type="password" class="form-control" id="password" placeholder="Enter password" /></td>
  </tr>

  <tr class="control_showing">
    <?php 
    if ($action_function == "edit_row") {
    ?>
      <input type="hidden" id="row_id" value="<?php echo $row_id; ?>" />
      <td colspan="2" class="text-center">
        <button class="btn btn-success" type="submit" onclick="run_controller(this,'edit_row');">💾 Save Changes</button>
      </td>
    <?php } else { ?>
      <td colspan="2" class="text-center">
        <button class="btn btn-success" type="submit" onclick="run_controller(this,'add_row');">➕ Add User</button>
      </td>
    <?php } ?>
  </tr>

  <tr><td colspan="2" align="center"><div id="response-msg"></div></td></tr>
</table>

  </div>
</div>

<?php
include "footer.php";
?>


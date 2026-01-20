<?php
include "header.php";

if($_SESSION['user_role_account'] != 1) {
	if(file_exists('../index.php')) echo "<script> window.top.location.href = '../index.php';</script>";
	else if(file_exists('../../index.php')) echo "<script> window.top.location.href = '../../index.php';</script>";
	exit;
}
?>
<!-- Single Page Header start -->
<div class="container-fluid py-5"></div>
<!-- Single Page Header End -->

<!-- Users Page Start -->
<div class="container-fluid py-5">
    <div class="container py-5" dir="ltr">
        
<?php
$where_user_role_account = "";
if(is_numeric($_GET['user_role_account'])) {
  $where_user_role_account = " AND user_role_account=" . intval($_GET['user_role_account']);  
}
?>

<script>
function delete_row_fun(element, action, row_id) {
	var ok = confirm("Are you sure you want to delete this record?");
	if(!ok) return;
	
	var pass_data = {
		'action': action,
		'row_id': row_id,
	}

	$.ajax({
		type: "POST",
		url: "users_fun.php",
		data: pass_data,
		success: function(response) {
			response = JSON.parse(response.trim());
			if(response.status == "ok") {
				$(element).parent().parent().fadeOut('slow');
			}
			else if(response.status == "error") {
				alert(response.contentMsg);
			}
	 	}
	});
}
</script>

<br>
<center>
<a class="btn btn-primary" style="background:#fdfdfd;border:1px solid #aaa;color:#000;" href="users_template.php?action=add_row&area_id=<?php echo $area_id;?>">
	<span style="float:right;margin-right:10px;">Add New User</span>
</a>
</center>

<style>
.role-buttons {
  text-align: center;
  margin-bottom: 20px;
}

.role-buttons a {
  display: inline-block;
  margin: 6px;
  padding: 10px 22px;
  border-radius: 30px;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
  border: 1px solid #81c408;
  color: #81c408;
  background: #fff;
  box-shadow: 0 2px 8px rgba(0, 123, 255, 0.15);
}

.role-buttons a:hover {
  background: #81c408;
  color: #fff;
  box-shadow: 0 3px 12px rgba(0, 123, 255, 0.3);
  transform: translateY(-2px);
}

.role-buttons a.active {
  background: #81c408;
  color: #fff;
  box-shadow: 0 3px 10px rgba(0, 123, 255, 0.3);
}
</style>

<div class="role-buttons">
  <a href="?user_role_account=all" class="btn-role">All</a>
  <a href="?user_role_account=1" class="btn-role">Admins</a>
  <a href="?user_role_account=2" class="btn-role">Sellers</a>
  <a href="?user_role_account=3" class="btn-role">Buyers</a>
  <a href="?user_role_account=4" class="btn-role">Courier</a>
</div>

<script>
// Highlight active button automatically
const params = new URLSearchParams(window.location.search);
const role = params.get("user_role_account") || "all";
document
  .querySelectorAll(".role-buttons a")
  .forEach(a => {
    if (a.href.includes("user_role_account=" + role)) {
      a.classList.add("active");
    }
  });
</script>

<br><br>

<table class="table" dir="ltr" style="font-size:0.9em;text-align:center">
    <tr>
        <th width="3%"></th>
        <th width="7%">Image</th>
        <th width="16%">Full Name</th>
        <th width="16%">Phone</th>
        <th width="8%">Account Type</th>
        <th width="12%">City</th>
        <th width="16%">Address</th>
        <th width="4%">Edit</th>
        <th width="4%">Delete</th>
    </tr>
    <?php
	$sql = "SELECT 
		users.id,
		users.fullname,
		users.business_name,
		users.img,
		users.phone,
		users.address,
		users.user_role_account,
		area.name as area_name 
		FROM users 
		LEFT JOIN area ON users.area_id=area.id   
		WHERE 1  
		$where_user_role_account
		AND users.is_canceled_row=0 
		ORDER BY users.id DESC";
		
    $query = mysqli_query($connect, $sql);
    $row_number = 0;
    while ($row = mysqli_fetch_array($query)) {
        $id = $row['id'];
        $row_number++;
        $users_id = $row['id'];
        ?>
        <tr>
            <td><?php echo $row_number; ?></td>
            <td>
				<?php if($row['img']) { ?>
					<img src="uploads/<?php echo $row['img']; ?>" style="width:77px;border-radius:5px;" />
				<?php } ?>
			</td>
            <td ><?php echo $row['fullname']."<br><strong style='color:green;'> ".$row['business_name']; ?></strong></td>
            <td><?php echo $row['phone']; ?></td>
            <td>
				<?php 
                    $roles = [
                        1 => "<span style='color:#000000'>Admin</span>",     // أسود
                        2 => "<span style='color:#1a73e8'>Seller</span>",    // أزرق
                        3 => "<span style='color:#0a8f08'>Buyer</span>",     // أخضر
                        4 => "<span style='color:#d93025'>Courier</span>"    // أحمر
                    ];

					echo isset($roles[$row['user_role_account']]) ? $roles[$row['user_role_account']] : "Unknown";
				?>
			</td>
            <td><span style="color:#555;font-weight:bold;"><?php echo $row['area_name']; ?></span></td>
            <td><?php echo $row['address']; ?></td>
            <td><a title="Edit" href="users_template.php?action=edit_row&row_id=<?php echo $id; ?>"><img width=28 src="assets/icons/update.png" title="Edit User"/></a></td>
            <td><a onclick="delete_row_fun(this,'cancel_row',<?php echo $id; ?>)" title="Delete"><img width=28 src="assets/icons/delete.png" title="Delete User"/></a></td>
        </tr>
    <?php 
    }
     ?>
</table>
    </div>
</div>

<?php include "footer.php"; ?>


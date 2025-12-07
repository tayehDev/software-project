<?php
include "header.php";

if ($_SESSION['user_role_account'] != 1) {
    if (file_exists('../index.php')) echo "<script> window.top.location.href = '../index.php';</script>";
    else if (file_exists('../../index.php')) echo "<script> window.top.location.href = '../../index.php';</script>";
    exit;
}
?>
<!-- Single Page Header start -->
<div class="container-fluid py-5" style="background-color:#f7f9f9;"></div>
<!-- Single Page Header End -->

<!-- Head Images Page Start -->
<div class="container-fluid py-5" style="background-color:#f0f8f0;">
    <div class="container py-5" dir="ltr">

<script>
function delete_row_fun(element, action, row_id) {
    var ok = confirm("Are you sure you want to delete this record?");
    if (!ok) return;

    var pass_data = {
        'action': action,
        'row_id': row_id
    };

    $.ajax({
        type: "POST",
        url: "head_img_fun.php",
        data: pass_data,
        success: function(response) {
            response = JSON.parse(response.trim());
            if (response.status == "ok") {
                $(element).closest('tr').fadeOut('slow');
            } else if (response.status == "error") {
                alert(response.contentMsg);
            }
        }
    });
}
</script>

<?php 
$sql = "SELECT 
            head_img.id,
            head_img.img,
            head_img.topic
        FROM head_img 
        WHERE 1  
        ORDER BY head_img.id DESC";

$query = mysqli_query($connect, $sql);
?>

<!-- Add Button -->
<div class="text-center mb-4">
    <a class="btn" style="background:#c3e88d;color:#fff;font-weight:bold;border:none;padding:10px 20px;border-radius:8px;"
       href="head_img_template.php?action=add_row">
       Add Advertisement
    </a>
</div>

<!-- Table -->
<table class="table table-bordered table-hover" dir="ltr" style="font-size:0.95em; background:#ffffff; border-radius:8px; overflow:hidden;">
    <thead style="background-color:#d4edc4; color:#2f4f2f; font-weight:bold;">
        <tr class="text-center">
            <th width="5%">#</th>
            <th width="15%">Image</th>
            <th width="50%">Title</th>
            <th width="15%">Edit</th>
            <th width="15%">Delete</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $row_number = 0;
    while ($row = mysqli_fetch_array($query)) {
        $row_number++;
        $id = $row['id'];
        $topic = htmlspecialchars($row['topic']);
        ?>
        <tr>
            <td class="text-center"><?php echo $row_number; ?></td>
            <td class="text-center">
                <?php if ($row['img']) { ?>
                    <img src="uploads/<?php echo $row['img']; ?>" style="width:80px; height:50px; border-radius:5px; object-fit:cover;" />
                <?php } ?>
            </td>
            <td><?php echo $topic; ?></td>
            <td class="text-center">
                <a href="head_img_template.php?action=edit_row&row_id=<?php echo $id; ?>" title="Edit">
                    <img width="28" src="assets/icons/update.png" alt="Edit" />
                </a>
            </td>
            <td class="text-center">
                <a onclick="delete_row_fun(this,'cancel_row',<?php echo $id; ?>)" title="Delete">
                    <img width="28" src="assets/icons/delete.png" alt="Delete" />
                </a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

    </div>
</div>

<?php
include "footer.php";
?>


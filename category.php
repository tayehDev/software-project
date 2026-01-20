<?php
include "header.php";

if ($_SESSION['user_role_account'] != 1) {
    if (file_exists('../index.php')) echo "<script>window.top.location.href = '../index.php';</script>";
    else if (file_exists('../../index.php')) echo "<script>window.top.location.href = '../../index.php';</script>";
    exit;
}
?>
<!-- Single Page Header -->
<div class="container-fluid py-5"></div>

<!-- Category Management Page -->
<div class="container-fluid py-5">
    <div class="container py-5" dir="ltr">

        <br>
        <a href="category_template.php">
            <div class="btn btn-primary" style="float:left; background:#81c408; border:none; color:white; font-size:1em; padding:10px 20px; border-radius:8px; margin-bottom:20px; text-decoration:none;">
                Add Category
            </div>
        </a>
        <br><br>

        <table class="table table-hover" dir="ltr" style="font-size:0.9em; border-collapse: separate; border-spacing: 0 8px; width:100%; background:#f9fdf6;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category Name</th>
                    <th>Order</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM category WHERE 1 ORDER BY category.id DESC";
                $query = mysqli_query($connect, $sql);
                $row_number = 0;

                while ($row = mysqli_fetch_array($query)) {
                    $row_number++;
                    $id = $row['id'];
                    $category_name = htmlspecialchars($row['name']);
                    $sort = intval($row['sort']);
                ?>
                    <tr>
                        <td><?php echo $row_number; ?></td>
                        <td style="color:#3a6b00; font-weight:600;"><?php echo $category_name; ?></td>
                        <td style="color:#c0392b; font-weight:bold;"><?php echo $sort; ?></td>
                        <td>
                            <a href="category_template.php?action=update_form&category_id=<?php echo $id; ?>">
                                <img width="28" src="assets/icons/update.png" title="Edit category" />
                            </a>
                        </td>
                        <td>
                            <a href="category_backDB.php?action=_DELETE_DATA_&category_id=<?php echo $id; ?>" onclick="return confirm('Are you sure you want to delete this record?')">
                                <img width="28" src="assets/icons/delete.png" title="Delete category" />
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</div>

<?php include "footer.php"; ?>


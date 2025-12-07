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

<!-- Category Form Page -->
<div class="container-fluid py-5">
    <div class="container py-5" dir="ltr">

        <?php
        $name = "";
        $sort = "";
        $action_function = "new";

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update_form') {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $action_function = "update";
                $category_id = $_GET['category_id'];
                $sql = "SELECT * FROM category WHERE category.id = $category_id";
                $query = mysqli_query($connect, $sql);
                $row = mysqli_fetch_array($query);
                $name = htmlspecialchars($row['name']);
                $sort = intval($row['sort']);
            } else {
                die("Invalid request.");
            }
        }
        ?>

        <form method="POST" action="category_backDB.php" enctype="multipart/form-data">
            <table class="table table-borderless" dir="ltr" style="font-size:0.95em; width:50%;">
                
                <tr>
                    <td width="25%">Category Name</td>
                    <td>
                        <input class="form-control" type="text" name="name" required value="<?php echo $name; ?>" />
                    </td>
                </tr>

                <tr>
                    <td>Display Order</td>
                    <td>
                        <input class="form-control" type="number" name="sort" required value="<?php echo $sort; ?>" />
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <?php if ($action_function == "update") { ?>
                            <input type="hidden" name="category_id" value="<?php echo $category_id; ?>" />
                            <input type="submit" class="btn btn-success" name="_SAVE_UPDATE_BTN_" value="Update" />
                        <?php } else { ?>
                            <input type="submit" class="btn btn-success" name="_SAVE_UPDATE_BTN_" value="Save" />
                        <?php } ?>
                    </td>
                </tr>

            </table>
        </form>

    </div>
</div>

<?php include "footer.php"; ?>


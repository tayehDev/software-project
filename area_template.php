<?php
include "header.php";

if ($_SESSION['user_role_account'] != 1) {
    if (file_exists('../index.php')) echo "<script> window.top.location.href = '../index.php';</script>";
    else if (file_exists('../../index.php')) echo "<script> window.top.location.href = '../../index.php';</script>";
    exit;
}
?>

<!-- Page Header -->
<div class="container-fluid py-5"></div>

<!-- Main Content -->
<div class="container py-5" dir="ltr">

<?php
$name = "";
$action_function = "new";

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update_form') {
    if (isset($_SERVER['HTTP_REFERER'])) {
        $action_function = "update";
        $area_id = $_GET['area_id'];
        $sql = "SELECT * FROM area WHERE id = $area_id";
        $query = mysqli_query($connect, $sql);
        $row = mysqli_fetch_array($query);
        $name = htmlspecialchars($row['name']);
    } else {
        die("Invalid request");
    }
}
?>

<div class="card shadow-lg border-0 rounded-4" style="max-width: 600px; margin: auto;">
    <div class="card-body p-4">
        <h4 class="text-center mb-4 fw-bold text-primary">
            <?php echo ($action_function == "update") ? "Update City" : "Add New City"; ?>
        </h4>

        <form method="POST" action="area_backDB.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">City Name</label>
                <input type="text" id="name" name="name" class="form-control form-control-lg" 
                       placeholder="Enter city name" required 
                       value="<?php echo $name; ?>">
            </div>

            <?php if ($action_function == "update") { ?>
                <input type="hidden" name="area_id" value="<?php echo $area_id; ?>">
            <?php } ?>

            <div class="text-center mt-4">
                <input type="submit" 
                       name="_SAVE_UPDATE_BTN_" 
                       class="btn btn-success px-4 py-2 rounded-3" 
                       value="<?php echo ($action_function == "update") ? "Update" : "Save"; ?>">
            </div>
        </form>
    </div>
</div>

</div>

<?php
include "footer.php";
?>


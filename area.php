<?php
include "header.php";

if ($_SESSION['user_role_account'] != 1) {
    if (file_exists('../index.php')) echo "<script> window.top.location.href = '../index.php';</script>";
    else if (file_exists('../../index.php')) echo "<script> window.top.location.href = '../../index.php';</script>";
    exit;
}
?>
<!-- Area Management Page -->
<div class="container-fluid py-5">
    <div class="container py-5" dir="ltr">

        <style>
            .custom-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background-color: #81c408;
                color: white !important;
                font-weight: 500;
                border-radius: 10px;
                padding: 10px 20px;
                font-size: 1.05em;
                transition: 0.3s ease;
                border: none;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
                text-decoration: none !important;
            }

            .custom-btn:hover {
                background-color: #6aa207;
                transform: translateY(-2px);
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            }

            .table {
                border-collapse: separate;
                border-spacing: 0 8px;
                width: 100%;
                background: #f9fdf6;
            }

            .table th {
                background-color: #81c408;
                color: white;
                border: none;
                text-align: center;
                padding: 12px;
                font-size: 1em;
                border-radius: 8px 8px 0 0;
            }

            .table td {
                background-color: #fff;
                text-align: center;
                padding: 10px;
                vertical-align: middle;
                border-top: 1px solid #e3e3e3;
            }

            .table tr:hover td {
                background-color: #f2f8eb;
            }

            .table img {
                transition: transform 0.2s ease;
            }

            .table img:hover {
                transform: scale(1.15);
            }

            .city-name {
                color: #3a6b00;
                font-weight: 600;
            }

            .sort-number {
                color: #c0392b;
                font-weight: bold;
            }
        </style>

        <br><br><br>

        <!-- Add Button moved to the LEFT -->
        <div class="d-flex justify-content-start mb-4">
            <a href="area_template.php" class="custom-btn">
                Add City
            </a>
        </div>

        <table class="table shadow-sm rounded">
            <tr class="firstTR">
                <th width=5% >#</th>
                <th>City Name</th>
                <th width=5% >Edit</th>
                <th width=5% >Delete</th>
            </tr>

            <?php
            $sql = "SELECT * FROM area WHERE is_canceled_row=0 ORDER BY area.id DESC";
            $query = mysqli_query($connect, $sql);
            $row_number = 0;

            while ($row = mysqli_fetch_array($query)) {
                $row_number++;
                $id = $row['id'];
                $area_name = htmlspecialchars($row['name']);
                $sort = intval($row['sort']);
            ?>
                <tr>
                    <td><?php echo $row_number; ?></td>
                    <td><span class="city-name"><?php echo $area_name; ?></span></td>
                    <td>
                        <a href="area_template.php?action=update_form&area_id=<?php echo $id; ?>">
                            <img width="28" src="assets/icons/update.png" title="Edit city information" />
                        </a>
                    </td>
                    <td>
                        <a href="area_backDB.php?action=_DELETE_DATA_&area_id=<?php echo $id; ?>"
                            onclick="return confirm('Are you sure you want to delete this record?')">
                            <img width="28" src="assets/icons/delete.png" title="Delete city" />
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>

<?php include "footer.php"; ?>


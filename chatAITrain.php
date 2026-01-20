<?php
include "header.php";

if($_SESSION['user_role_account'] != 1 && $_SESSION['user_role_account'] != 2) {
    if(file_exists('index.php')) echo "<script> window.top.location.href = 'index.php';</script>";
    else if(file_exists('../index.php')) echo "<script> window.top.location.href = '../index.php';</script>";
    exit;
}
?>
<div class="container-fluid py-5" style="background-color:#f7f9f7;"></div>

<div class="container-fluid py-5" style="background-color:#f0f8e8;">
    <div class="container py-5" dir="ltr">

<?php
// =======================
// Get Sellers (User Role 2)
// =======================
$sellers_query = mysqli_query($connect, "
    SELECT id, CONCAT(fullname,' ',business_name) AS username
    FROM users
    WHERE user_role_account = 2
    ORDER BY fullname ASC
");
?>

<!-- Search Form -->
<form method="GET" class="mb-4">
    <div class="row g-2">

        <!-- Search Text -->
        <div class="col-md-6">
            <input type="text" name="search" class="form-control"
                   placeholder="Search Question, or Answer..."
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        </div>
        <?php
        if($_SESSION['user_role_account'] == 1)
        {
        ?>
        <!-- Seller Filter -->
        <div class="col-md-4">
            <select name="seller_id" class="form-control">
                <option value="">Filter by Seller</option>

                <?php while($s = mysqli_fetch_array($sellers_query)) { ?>
                    <option value="<?php echo $s['id']; ?>"
                        <?php if(isset($_GET['seller_id']) && $_GET['seller_id'] == $s['id']) echo "selected"; ?>>
                        <?php echo $s['username']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <?php
        }
        ?>

        <!-- Search Button -->
        <div class="col-md-2">
            <button type="submit" class="btn"
                style="background:#c3e88d;color:#fff;font-weight:bold;border:none;padding:10px 20px;border-radius:8px;">
                Search
            </button>
        </div>

    </div>
</form>

<script>
function delete_row_fun(element, action, row_id) {
    var ok = confirm("Are you sure you want to delete this record?");
    if(!ok) return;

    var pass_data = { 'action': action, 'row_id': row_id };

    $.ajax({
        type: "POST",
        url: "chatAITrain_fun.php",
        data: pass_data,
        success: function(response) {
            response = JSON.parse(response.trim());
            if(response.status == "ok") {
                $(element).closest('tr').fadeOut('slow');
            } else {
                alert(response.contentMsg);
            }
        }
    });
}
</script>

<?php
// =======================
// Filters
// =======================
$search = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';
$seller_id = isset($_GET['seller_id']) ? intval($_GET['seller_id']) : 0;
if($_SESSION['user_role_account'] == 2)$seller_id=$_SESSION['user_id'];
//echo $seller_id;
// =======================
// Main SQL with JOIN + Filters
// =======================
$sql = "
    SELECT 
        chat_conjunction.id,
        chat_conjunction.question,
        chat_conjunction.answer,
        chat_conjunction.auto_chat_seller_user_id,
        CONCAT(users.fullname,' - ',users.business_name) AS username
    FROM chat_conjunction
    LEFT JOIN users ON users.id = chat_conjunction.auto_chat_seller_user_id
    WHERE 1
";

if($search !== '') {
    $sql .= "
        AND (
            chat_conjunction.question LIKE '%$search%'
            OR chat_conjunction.answer LIKE '%$search%'
        )
    ";
}

if($seller_id > 0) {
    $sql .= " AND chat_conjunction.auto_chat_seller_user_id = $seller_id ";
}

$sql .= " ORDER BY chat_conjunction.id DESC";

$query = mysqli_query($connect, $sql);
?>

<div class="text-center mb-4">
    <a class="btn" style="background:#c3e88d;color:#fff;font-weight:bold;border:none;padding:10px 20px;border-radius:8px;"
        href="chatAITrain_template.php?action=add_row">
        Learn More
    </a>
</div>

<table class="table table-bordered table-hover" dir="ltr"
       style="font-size:0.95em; background:#fff; border-radius:8px; overflow:hidden;">
    <thead style="background-color:#d4edc4; color:#2f4f2f; font-weight:bold;">
        <tr class="text-center">
            <th width="5%">#</th>
            <th width="15%">User</th>
            <th width="20%">Q</th>
            <th>A</th>
            <th width="8%">Edit</th>
            <th width="8%">Delete</th>
        </tr>
    </thead>
    <tbody>

<?php
$row_number = 0;
while ($row = mysqli_fetch_array($query)) {
    $row_number++;
?>
        <tr>
            <td class="text-center"><?php echo $row_number; ?></td>

            <!-- Username -->
            <td class="text-center" style="font-weight:bold;color:#2f4f2f;">
                <?php echo htmlspecialchars($row['username']); ?>
            </td>

            <td><?php echo htmlspecialchars($row['question']); ?></td>
            <td style="white-space: pre-line;"><?php echo nl2br(htmlspecialchars($row['answer'])); ?></td>

            <td class="text-center">
                <a title="Edit" href="chatAITrain_template.php?action=edit_row&row_id=<?php echo $row['id']; ?>">
                    <img width="26" src="assets/icons/update.png" alt="Edit" />
                </a>
            </td>

            <td class="text-center">
                <a onclick="delete_row_fun(this,'cancel_row',<?php echo $row['id']; ?>)" title="Delete">
                    <img width="26" src="assets/icons/delete.png" alt="Delete" />
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


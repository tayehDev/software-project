<?php
include "header.php";
?>

<!-- Single Page Header start -->
<div class="container-fluid py-5"></div>
<!-- Single Page Header End -->

<?php
if (is_numeric($_GET['product_id'])) {
    if ($_SESSION['user_role_account'] == 1) {
        $sql = "SELECT * FROM products WHERE is_canceled_row=0 AND id={$_GET['product_id']}";
    } elseif ($_SESSION['user_role_account'] == 2) {
        $sql = "SELECT * FROM products WHERE is_canceled_row=0 AND id={$_GET['product_id']} AND by_user_id={$_SESSION['user_id']}";
    }

    $query = mysqli_query($connect, $sql);
    if ($row = mysqli_fetch_array($query)) {
        $product_id = $row['id'];
        $title = $row['title'];
        $description = $row['description'];
        $img = $row['img'];
        $category_id = $row['category_id'];
        $brand_copy = $row['brand_copy'];
        $price = $row['price'];
    } else {
        echo "<div class='alert alert-danger text-center'>Product does not exist or you do not have permissions</div>";
    }
}
?>

<script>
function publish_product(element, action, row_id) {
    var title = $("[name='title']").val();
    if (title.length < 3) {
        alert('Please enter the product name');
        return;
    }

    var description = $("[name='description']").val();
    var brand_copy = $("[name='brand_copy']").val();
    if (!brand_copy || brand_copy == 0) {
        alert('Please select the product status');
        return;
    }

    var category_id = $("[name='category_id'] option:selected").val();
    if (!category_id || category_id == 0) {
        alert('Please select a category');
        return;
    }

    var price = $("[name='price']").val();

    var formData = new FormData();
    formData.append('action', action);
    formData.append('row_id', row_id);
    formData.append('title', title);
    formData.append('description', description);
    formData.append('brand_copy', brand_copy);
    if ($("[name='img']")[0].files[0]) {
        formData.append('img', $("[name='img']")[0].files[0]);
    }
    formData.append('price', price);
    formData.append('category_id', category_id);

    $.ajax({
        type: "POST",
        url: "product_backDB.php",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            response = JSON.parse(response.trim());
            if (response.status == "ok") {
                $("#response-msg").css({color:"green"}).text(response.contentMsg);
                setTimeout(function() { location.reload(); }, 2000);
            } else if (response.status == "error") {
                $("#response-msg").css({color:"red"}).text(response.contentMsg);
            }
        }
    });
}
</script>

<!-- Product Form Start -->
<div class="container-fluid contact py-5">
    <div class="container py-5">
        <div class="p-5 rounded shadow-sm">
            <div class="row g-4" dir="ltr">
                <h4 class="mb-3 text-center">Product Details</h4>
                <hr>

                <div class="col-lg-6">
                    <input type="text" name="title" class="w-100 form-control mb-4" placeholder="Title" value="<?php echo $title; ?>">

                    <select class="form-control mb-4" name="category_id" id="category_id">
                        <option value="">Select Category</option>
                        <?php
                        $query_category = mysqli_query($connect, "SELECT * FROM category WHERE del=0 ORDER BY id DESC");
                        while ($row_category = mysqli_fetch_array($query_category)) {
                            $selected = ($category_id == $row_category['id']) ? "selected" : "";
                            echo "<option value='{$row_category['id']}' $selected>{$row_category['name']}</option>";
                        }
                        ?>
                    </select>

                    <input type="file" name="img" class="w-100 form-control mb-4">

                    <div style="position: relative;">
                        <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 1.5em; color:#555;">₪</span>
                        <input type="number" name="price" value="<?php echo $price; ?>" class="w-100 form-control mb-4 pl-5" placeholder="Price">
                    </div>

                    <textarea name="description" class="w-100 form-control mb-4" rows="5" placeholder="Description"><?php echo $description; ?></textarea>

                    <select class="form-control mb-4" name="brand_copy" id="brand_copy">
                        <option value="">Brand Status</option>
                        <option value="1" <?php if($brand_copy==1) echo "selected"; ?>>Original</option>
                        <option value="2" <?php if($brand_copy==2) echo "selected"; ?>>Copy</option>
                    </select>

                    <?php if(empty($_GET['product_id'])) { ?>
                        <button onclick="publish_product(this,'insert_product',null)" class="w-100 btn btn-primary py-3">Post</button>
                    <?php } else { ?>
                        <button onclick="publish_product(this,'update_product',<?php echo $product_id; ?>)" class="w-100 btn btn-success py-3">Save Changes</button>
                    <?php } ?>
                    <div id="response-msg" class="mt-2"></div>
                </div>

                <?php if(!empty($img)) { ?>
                <div class="col-lg-6 text-center">
                    <img src="uploads/<?php echo $img; ?>" class="img-fluid rounded shadow-sm" alt="Product Image">
                </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>
<!-- Product Form End -->

<style>
/* Form Card */
.container.contact .p-5 {
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* Inputs & Textareas */
.container.contact input[type="text"],
.container.contact input[type="number"],
.container.contact input[type="file"],
.container.contact select,
.container.contact textarea {
    border-radius: 10px;
    border: 1px solid #ccc;
    padding: 12px 15px;
    font-size: 1em;
    transition: all 0.3s ease;
}

.container.contact input:focus,
.container.contact textarea:focus,
.container.contact select:focus {
    border-color: #66a5ff;
    box-shadow: 0 0 5px rgba(102,165,255,0.4);
    outline: none;
}

/* Buttons */
.container.contact .btn {
    font-weight: bold;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.container.contact .btn:hover {
    opacity: 0.9;
}

/* Response Message */
#response-msg {
    font-weight: bold;
}

/* Image Preview */
.container.contact .col-lg-6 img {
    max-height: 350px;
    object-fit: contain;
    border-radius: 12px;
    border: 1px solid #ddd;
    margin-top: 15px;
}

/* Heading */
.container.contact h4 {
    color: #333;
    font-weight: bold;
}

/* Responsive */
@media (max-width: 991px) {
    .container.contact .col-lg-6 {
        margin-bottom: 20px;
    }
}

/* Price input with currency */
.container.contact div[style*="position: relative;"] input {
    padding-left: 35px;
}
</style>

<?php
include "footer.php";
?>


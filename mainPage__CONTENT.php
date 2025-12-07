<?php
try{
  include "config.php"; 
}
catch (Exception $e) {
    echo "خطأ: " . $e->getMessage();
}
?>

<div class="row g-4 justify-content-center">




<?php
$minPrice=0;
$maxPrice=0;
if(!empty($_POST['product_title']))
{ 
  $product_title=mysqli_real_escape_string($connect, $_POST['product_title']);
  $where_product_title=" AND products.title LIKE '%$product_title%' ";
}
if(!empty($_POST['category_id']) && $_POST['category_id']!="all")
{
  $category_id=intval($_POST['category_id']);
  $where_category_id=" AND products.category_id =$category_id ";
}

if(!empty($_POST['brand_copy']))
{
  $brand_copy=mysqli_real_escape_string($connect, $_POST['brand_copy']);
  $where_brand_copy=" AND products.brand_copy IN ($brand_copy) ";
}

if(!empty($_POST['minPrice']))
{
  $minPrice=round(floatval($_POST['minPrice']),2);
  $where_minPrice=" AND products.price >= $minPrice ";
}
if(!empty($_POST['maxPrice']))
{
  $maxPrice=round(floatval($_POST['maxPrice']),2);
  $where_maxPrice=" AND products.price <= $maxPrice ";
}

if(!empty($_POST['product_order_view']))
{
  $product_order_view=$_POST['product_order_view'];
  if($_POST['product_order_view']=="max_id")$orderBy=" ORDER BY products.id DESC ";
  else if($_POST['product_order_view']=="min_price")$orderBy=" ORDER BY products.price ASC ";

  
}


?>

<?php
$sql="
SELECT 
products.*,
category.name as category_name,
CONCAT(users.fullname,' ',users.nickname) AS user_name,
CASE
    -- المنتجات التي اشتراها آخرون اشتروا منتجاتك (عدا منتجاتك)
    WHEN products.id IN (
        SELECT DISTINCT o1.product_id 
        FROM orders_list o1
        WHERE o1.by_user_id IN (
            SELECT DISTINCT by_user_id 
            FROM orders_list 
            WHERE product_id IN (
                SELECT product_id 
                FROM orders_list 
                WHERE by_user_id = $_SESSION[user_id]
            )
            AND by_user_id != $_SESSION[user_id]
        )
        AND o1.product_id NOT IN (
            SELECT product_id 
            FROM orders_list 
            WHERE by_user_id = $_SESSION[user_id]
        )
        AND o1.is_canceled_row = 0
    ) THEN 'recommended'
    
    -- باقي المنتجات
    ELSE 'general'
END AS section_type,

-- ترتيب حسب عدد المشتريات للمنتجات المقترحة
(SELECT COUNT(*) FROM orders_list WHERE orders_list.product_id = products.id) AS purchase_count

FROM products 
LEFT JOIN category ON category.id = products.category_id 
LEFT JOIN users ON users.id = products.by_user_id  
WHERE 1 
AND products.is_canceled_row = 0 
ORDER BY 
    CASE 
        WHEN section_type = 'recommended' THEN 1
        ELSE 2
    END,
    purchase_count DESC
";

if(!empty($where_product_title) ||!empty($where_category_id) ||!empty($where_brand_copy) ||!empty($where_minPrice) ||!empty($where_maxPrice) || !empty($orderBy) || empty($_SESSION['user_id']) ) 
{
    $sql="SELECT 
        products.*,
        category.name as category_name,
        concat(fullname,' ',nickname) AS user_name 
        FROM products 
        LEFT JOIN category ON category.id=products.category_id 
        LEFT JOIN users ON users.id=products.by_user_id  
        WHERE 1 
        $where_product_title
        $where_category_id
        $where_brand_copy

        $where_minPrice
        $where_maxPrice
        AND products.is_canceled_row=0 
        $orderBy
        ";
            
}




//echo $sql;

$query = mysqli_query($connect, $sql);
while($row = mysqli_fetch_array($query))
{
  $desc=$row['description'];
  $title=$row['title'];

?>

  <div class="col-md-6 col-lg-6 col-xl-3">
      <div class="rounded position-relative fruite-item" >
          <div class="fruite-img" style="">
              <?php 
              if(!empty($row['img']))
              {
              ?>
              <img src="uploads/<?php echo $row['img'];?>" class="img-fluid w-100 rounded-top border border-secondary border-bottom-0" style="height:222px;" alt="">
              <?php
              }
              else
              {
              ?>
              <img src="assets/img/empty.png" class="img-fluid w-100 rounded-top border border-secondary border-bottom-0 "   alt="">
              <?php
              }
              ?>
          </div>
          <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;"><?php echo $row['category_name'];?></div>
          <div class="p-1 border border-secondary border-top-0 rounded-bottom" style="height:160px;text-align:center;">
              <?php
              $max = 20;
              if (mb_strlen($title, 'UTF-8') > $max) {
                  $title_display = mb_substr($title, 0, $max, 'UTF-8') . '...';
              } else {
                  $title_display = $title;
              }
              ?>
              
              <h6><?php echo $title_display;?></h6>

              <?php
              $max = 35;
              if (mb_strlen($desc, 'UTF-8') > $max) {
                  $desc_display = mb_substr($desc, 0, $max, 'UTF-8') . '...';
              } else {
                  $desc_display = $desc;
              }
              ?>
              <p style="height:33px;color:#bbb;font-size:0.9em;"><?php echo htmlspecialchars($desc_display, ENT_QUOTES, 'UTF-8'); ?></p>


              <div class="d-flex justify-content-between flex-lg-wrap">
                  <p class="text-dark fs-5 fw-bold mb-0"><?php echo $row['price'];?>₪</p>
                  <a href="product_details.php?product_id=<?php echo $row['id'];?>" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag "></i> المزيد</a>
              </div>
          </div>
      </div>
  </div>
    
  <?php
  }
  ?>  
    
</div>


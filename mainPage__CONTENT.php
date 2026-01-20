<?php
try{
  include "config.php"; 
}
catch (Exception $e) {
    echo "خطأ: " . $e->getMessage();
}
?>

<div class="row g-4 justify-content-center">


<style>
.profile-header {
    margin: 25px auto;
    width: 100%;
    max-width: 1100px;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    padding: 28px 35px;
    border-radius: 18px;
    box-shadow: 
        0 10px 25px rgba(0, 0, 0, 0.15),
        inset 0 0 15px rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.25);
    background-image: linear-gradient(135deg, #0066ff66 0%, #5500ff66 100%);
    color: #fff;
    display: flex;
    align-items: center;
    gap: 18px;
}

.profile-header-content span {
    font-size: 1.9rem;
    margin: 0;
    font-weight: 700;
    letter-spacing: 0.5px;
    font-family: "Segoe UI", Tahoma, sans-serif;
    color: #ffffff;
    text-shadow: 0 2px 6px rgba(0,0,0,0.3);
}


</style>

<?php
$minPrice=0;
$maxPrice=0;

$where="";

if (!empty($_POST['user_id']) ) {

    $user_id = intval($_POST['user_id']);


    $sql = "SELECT business_name, fullname FROM users WHERE id = $user_id";
    $query = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($query);

    ?>
    <div class="profile-header">
        <div class="profile-header-content">
            <span style="color:#000;"><?php echo $row['business_name']; ?>
            <?php if(!empty($_SESSION['user_id'])){?><a style="" href="messenger.php?user_id=<?php echo $_REQUEST['user_id'] ;?>" class="btn btn-secondary">
                <i class="fas fa-envelope"></i> Contact
            </a>
            <?php
            }
            ?>
        </span><!--span style="text-align:right;margin-left:33px;font-size:1.3em;"><?php echo $row['fullname']; ?></span-->
            
        </div>
        
    </div>
    <?php 
} 


if(!empty($_POST['product_title']))
{ 
  $product_title=mysqli_real_escape_string($connect, $_POST['product_title']);
  $where.=" AND products.title LIKE '%$product_title%' ";
}
if(!empty($_POST['category_id']) && $_POST['category_id']!="all")
{
  $category_id=intval($_POST['category_id']);
  $where.=" AND products.category_id =$category_id ";
}

if(!empty($_POST['brand_copy']))
{
  $brand_copy=mysqli_real_escape_string($connect, $_POST['brand_copy']);
  $where.=" AND products.brand_copy IN ($brand_copy) ";
}

if(!empty($_POST['minPrice']))
{
  $minPrice=round(floatval($_POST['minPrice']),2);
  $where.=" AND products.price >= $minPrice ";
}
if(!empty($_POST['maxPrice']))
{
  $maxPrice=round(floatval($_POST['maxPrice']),2);
  $where.=" AND products.price <= $maxPrice ";
}

if(!empty($_POST['product_order_view']))
{
  $product_order_view=$_POST['product_order_view'];
  if($_POST['product_order_view']=="max_id")$orderBy=" ORDER BY products.id DESC ";
  else if($_POST['product_order_view']=="min_price")$orderBy=" ORDER BY products.price ASC ";

  
}



$sql="SELECT 
    products.*,
    category.name as category_name,
    concat(fullname,' ',business_name) AS user_name 
    FROM products 
    LEFT JOIN category ON category.id=products.category_id 
    LEFT JOIN users ON users.id=products.by_user_id  
    WHERE 1 
    $where
    AND products.is_canceled_row=0 
    $orderBy
    ";
    
//echo $sql;   


$query = mysqli_query($connect, $sql);
while($row = mysqli_fetch_array($query))
{
  $desc=$row['description'];
  $title=$row['title'];

?>

  <div class="col-md-6 col-lg-6 col-xl-3">
      <div class="rounded position-relative fruite-item" title="<?php echo $row['ai_analysis']!="wait"?$row['ai_analysis']:'';?>" >
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


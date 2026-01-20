


<script>


function call_page(set_category)
{
  
  var category_id=""	;
  if(set_category!=null)category_id=set_category;
	

	var brand_copy = "";
	$('input[name="brand_copy"]:checked').each(function() {
      brand_copy+=$(this).val()+",";
  });
  brand_copy=brand_copy.replace(/^,|,$/g, '');


  
  var minPrice = $('#rangeInputmin').val();
  var maxPrice = $('#rangeInputmax').val();

  
  
  
  var product_title = $('#product_title').val();

  var product_order_view = $('#product_order_view').val();

  
  
  //alert(new_url);
  $("#page_content").load("mainPage__CONTENT.php", { 
      category_id: category_id, 
      product_order_view: product_order_view, 
      minPrice: minPrice, 
      maxPrice: maxPrice, 
      user_id:'<?php echo $_GET["user_id"];?>',
      brand_copy: brand_copy, 
      product_title: product_title 
  });
  //alert(1);

  //location.href=new_url;
  
}

</script>

<script>
function handleKeyPress(event) {
  // Check if the pressed key is Enter (key code 13)
  if (event.keyCode === 13) {
    // Execute your function here, for example:
    call_page(null);
  }
}

</script>



<div class="container-fluid fruite py-0">
    <div class="container py-5" dir="ltr">
        <div class="tab-class text-center">
             <div class="row g-4">

                <div class="col-lg-12 text-end">

                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-12">
                <div class="row g-4">
                    <div class="col-xl-9">
                        <div class="input-group w-100 mx-auto d-flex" dir="ltr">
                            <input type="search" class="form-control p-3" placeholder="Search in Marketpalce" aria-describedby="search-icon-1" style="border-radius:10px 0 0 10px !important;" id="product_title" onkeyup="handleKeyPress(event)" value="<?php echo htmlspecialchars($product_title);?>" >
                            <span id="search-icon-1" class="input-group-text p-3" style="border-radius:0px 10px 10px 0px !important;"><i class="fa fa-search"></i></span>
                        </div>
                    </div>



<div class="col-xl-3">
    <div class="p-3 rounded-4 d-flex align-items-center justify-content-between mb-4"
         style="background: linear-gradient(135deg, #f0f4ff, #d9e4ff); 
                box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
        
        <!-- Label -->
        <label for="product_order_view" style="width:120px;" class="fw-bold text-primary mb-0 me-3">
            Sort By:
        </label>
        
        <!-- Select Box -->
        <select id="product_order_view" 
                onchange="call_page(null);" 
                class="form-select form-select-sm fw-semibold"
                style="min-width: 140px; 
                       border: 2px solid #a0c4ff; 
                       border-radius: 12px; 
                       background-color: #ffffff; 
                       padding: 4px 8px; 
                       transition: all 0.3s ease; 
                       cursor: pointer;">
            <option value="max_id" <?php if($product_order_view=="max_id") echo "selected"; ?>>
                Latest
            </option>
            <option value="min_price" <?php if($product_order_view=="min_price") echo "selected"; ?>>
                Cheapest
            </option>
        </select>
    </div>
</div>

<style>
    #product_order_view:hover {
        border-color: #4a90ff;
        box-shadow: 0 0 8px rgba(74, 144, 255, 0.4);
        transform: translateY(-2px);
    }
</style>



                </div>
                <div class="row g-4">
                    <div class="col-lg-3">
                        <div class="row g-4">
                        
            
                            <div class="col-lg-12">
                              <div class="mb-3">
                                  <h4>Brand</h4>
                                  <div class="form-check text-start">
                                      <input type="checkbox" class="form-check-input bg-primary border-0" id="brand_copy1" name="brand_copy" value="1" style="float:left;" onclick="call_page(null);" >
                                      <label class="form-check-label" for="brand_copy1" style="float:left;margin-right:20px;">Original</label>
                                  </div>
                                  <div class="form-check text-start">
                                      <input type="checkbox" class="form-check-input bg-primary border-0" id="brand_copy2" name="brand_copy" value="2" style="float:left;" onclick="call_page(null);" >
                                      <label class="form-check-label" for="brand_copy2" style="float:left;margin-right:20px;">Copy</label>
                                  </div>
                              </div>



                            </div>

                            <div class="col-lg-12">
                                <div class="row align-items-center mb-2">
                                    <!-- Min -->
                                    <div class="col-4">
                                        <label for="rangeInputmin" class="form-label" style="font-size:15px;">Min Price</label>
                                        <input type="number" 
                                                style="text-align:center;"
                                                min="0"
                                               class="form-control form-control-sm" 
                                               id="rangeInputmin" 
                                               value="">
                                    </div>

                                    <!-- Max -->
                                    <div class="col-4">
                                        <label for="rangeInputmax" class="form-label" style="font-size:15px;">Max Price</label>
                                        <input type="number" 
                                              style="text-align:center;"
                                               class="form-control form-control-sm" 
                                               min="0"
                                               id="rangeInputmax" 
                                               value="">
                                    </div>

                                    <!-- زر البحث -->
                                    <div class="col-1 d-flex">
                                    
                                        <button type="button" class="btn btn-primary " onclick="call_page(null);" style="margin-top:28px;padding:5px;color:#fff;font-size:0.8em;">
                                            Search
                                        </button>
                                    </div>
                                </div>
                            </div>


                        
                          <div class="col-lg-12">

                            <!-- القائمة الجانبية -->
                            <nav class="modern-sidebar" dir="ltr">
                                <ul class="nav flex-column">
                                    <li class="nav-item" onclick="call_page('all');">
                                        <a class="nav-link active">
                                            <span>ALL</span>
                                        </a>
                                    </li>
                                    <?php
                                    $sql="SELECT * FROM category WHERE 1 AND del=0 ORDER BY sort ASC";
                                    $query = mysqli_query($connect, $sql);
                                    while ($row = mysqli_fetch_array($query)) {
                                    ?>
                                    <li class="nav-item" onclick="call_page(<?php echo $row['id'];?>);">
                                        <a class="nav-link " onclick="$('.nav-link').removeClass('active');$(this).addClass('active');">
                                            <span><?php echo $row['name'];?></span>
                                        </a>
                                    </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </nav>

                            <style>
                            :root {
                                --primary: #011432;   
                                --secondary: #e75423; 
                                --light: #F4F6F8;     /* رمادي فاتح */
                                --dark: #45595B;      /* رمادي غامق */
                            }

                            /* القائمة الجانبية */
                            .modern-sidebar {
                                width: 240px;
                                min-height: 100vh;
                                background:#fdfdfd;
                                padding: 20px 10px;
                                border-left:1px solid #ccc;
                            }

                            /* العناصر */
                            .modern-sidebar .nav-link {
                                display: block;
                                padding: 12px 18px;
                                margin: 6px 0;
                                border-radius: 8px;
                                color: var(--primary);
                                font-size: 15px;
                                font-weight: 500;
                                text-decoration: none;
                                transition: all 0.3s ease;
                                background: transparent;
                                cursor:pointer;
                            }

                            /* hover */
                            .modern-sidebar .nav-link:hover {
                                background: var(--secondary);
                                color: #fff;
                                transform: translateX(5px);
                            }

                            /* active */
                            .modern-sidebar .nav-link.active {
                                background: #fff;
                                color: var(--primary);
                                font-weight: 600;
                                border-left: 5px solid var(--secondary);
                                box-shadow: 0 3px 8px rgba(0,0,0,0.15);
                            }
                            </style>

                              
                          </div>


                            
                            
                        </div>
                    </div>
                    <div class="col-lg-9" id="page_content">
                      <script>
                      $(document).ready(function(){
                          $("#page_content").load("mainPage__CONTENT.php", { 
                              user_id:'<?php echo $_GET["user_id"];?>',

                          });
                      });
                      </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





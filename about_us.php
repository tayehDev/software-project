<?php
include "header.php";

?>

<!-- Single Page Header start -->
<div class="container-fluid py-5">
</div>
<!-- Single Page Header End -->

<!-- About Start -->
<div class="container-fluid contact py-5">
    <div class="container py-5">
        <div class="p-5 bg-light rounded">
            <div class="row g-4">
                <div class="col-12">
                    <div class="text-center mx-auto" style="max-width: 700px;">
                        <h1 class="text-primary">About Us</h1>
                        <p class="mb-4"><?php echo $about; ?></p>
                    </div>
                </div>

                <div class="col-lg-12">
                
                    <?php
                    $sql="SELECT * FROM about_us WHERE 1 ";
                    $query = mysqli_query($connect, $sql);
                    while ($row = mysqli_fetch_array($query)) 
                    {
                    ?>
                    <div class="d-flex p-4 rounded mb-4 bg-white">
                        <i class="fas fa-info fa-2x text-primary me-4"></i>
                        <div class="me-2">
                            <h4><?php echo $row['topic'];?></h4>
                            <p class="mb-2"><?php echo $row['description'];?></p>
                        </div>
                    </div>
                    <?php

                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>


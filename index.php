<?php
include "header.php";
?>



<?php
if($_GET['search']!="yes")
{
?>
<!-- Hero Start -->
<div class="container-fluid py-2 mb-1 hero-header">
    <div class="container py-5">
        <div class="row g-5 align-items-center">
            <div class="col-md-12 col-lg-12">
                <div id="carouselId" class="carousel slide position-relative" data-bs-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        <?php
                        $sql="SELECT * FROM head_img WHERE 1 ";
                        $query = mysqli_query($connect, $sql);
                        $first='active';
                        while ($row = mysqli_fetch_array($query)) 
                        {
                        ?>
                        <div class="carousel-item <?php echo $first;?> rounded">
                            <img src="uploads/<?php echo $row['img'];?>" class="img-fluid w-100 h-100 bg-secondary rounded" style="height:300px !important;" alt="First slide">
                            <a href="#" class="btn px-4 py-2 text-white rounded"><?php echo $row['topic'];?></a>
                        </div>
                        <?php
                        $first='';
                        }
                        ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Hero End -->
<?php
}
else
{
?>
<div class="container-fluid py-2 mb-1 ">
    <div class="container py-5">

    </div>
</div>
<?php
}
?>


<?php
include "mainPage.php";
?>




<?php
include "bot.php";
?>


<?php
include "footer.php";
?>

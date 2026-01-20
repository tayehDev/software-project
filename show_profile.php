<?php
include "header.php";
?>


<?php
$sql = "SELECT users.*, area.name as area_name FROM users LEFT JOIN area ON area.id=users.area_id WHERE 1 AND users.id=$_GET[user_id]";
$query = mysqli_query($connect, $sql);
$row = mysqli_fetch_array($query);


$user_id = intval($_GET['user_id']);
$user_role = $row['user_role_account'];

$total = 0;
$count = 0;

// تحديد SQL حسب نوع الحساب
if ($user_role == 3) {
    // المشتري
    $sqlRate = "
        SELECT 
            evaluate_buyer_by_courier AS r1,
            evaluate_buyer_by_seller AS r2
        FROM orders_list
        WHERE by_user_id = $user_id
    ";
} elseif ($user_role == 2) {
    // البائع
    $sqlRate = "
        SELECT 
            evaluate_seller_by_buyer AS r1
        FROM orders_list
        WHERE by_user_id = $user_id
    ";
}

$queryRate = mysqli_query($connect, $sqlRate);

// حساب مجموع + عدد التقييمات > 0
while ($rt = mysqli_fetch_assoc($queryRate)) {
    foreach ($rt as $v) {
    //echo "----------$v----------------";
        if ($v > 0) {
            $total += $v;
            $count++;
        }
    }
}

$rating_avg = ($count > 0) ? round($total / $count, 2) : 0;
$rating_count = $count;

// عدد النجوم الممتلئة
$rating_stars = floor($rating_avg);

?>

    <style>
        :root {
            --primary: #011432;
            --secondary: #e75423;
            --accent: #7209b7;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4cc9f0;
            --gray: #6c757d;
            --border-radius: 16px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
        }

        .profile-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--light));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 0.5rem;
        }

        .page-header p {
            color: var(--gray);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .profile-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
            margin-bottom: 2rem;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            background: linear-gradient(135deg, var(--primary), var(--light));
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: cover;
        }

        .profile-content {
            display: flex;
            padding: 2rem;
            gap: 2rem;
            align-items: flex-start;
        }

        @media (max-width: 768px) {
            .profile-content {
                flex-direction: column;
                text-align: center;
            }
        }

        .profile-image {
            flex-shrink: 0;
            position: relative;
        }

        .profile-img-container {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #e0e0e0, #f0f0f0);
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-details {
            flex: 1;
        }

        .profile-name {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .profile-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--success), #4895ef);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--light);
            border-radius: 10px;
            transition: var(--transition);
        }

        .info-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .info-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary), var(--light));
            color: white;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .info-content h3 {
            font-size: 0.875rem;
            color: var(--gray);
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-content p {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
        }

        .map-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;

            color: var(--primary);
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);

        }

        .map-link:hover {
            transform: translateY(-3px);
            color: var(--dark);
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e9ecef, transparent);
            margin: 1.5rem 0;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--light));
            color: white;
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 15px rgba(0, 0, 0, 0.1);
        }

        .contact-section {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 2rem;
            margin-top: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="page-header">   

        </div>

        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-image">
                    <div class="profile-img-container">
                        <?php if(!empty($row['img'])) { ?>
                            <img src="uploads/<?php echo $row['img']; ?>" class="profile-img" alt="Profile Picture">
                        <?php } else { ?>
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <i class="fas fa-user fa-4x text-muted"></i>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            
            <div class="profile-content">
                <div class="profile-details">
                <h1 class="profile-name">
                    <?php echo $row['fullname']; ?>

                    <?php if($rating_avg > 0){ ?>
                        <span style="margin-left:10px; font-size:20px;">

                            <!-- عرض النجوم -->
                            <?php 
                            for($i=1; $i<=5; $i++){
                                if($i <= $rating_stars){
                                    echo '<span style="color:gold; font-size:22px;">★</span>';
                                } else {
                                    echo '<span style="color:#ccc; font-size:22px;">★</span>';
                                }
                            }
                            ?>

                            <!-- معدل التقييم -->
                            <span style="color:gold; font-size:20px;">
                                <?php echo $rating_avg; ?>
                            </span>

                            <!-- عدد التقييمات -->
                            <span style="color:#666; font-size:16px;">
                                (<?php echo $rating_count; ?> Rate)
                            </span>

                        </span>
                    <?php } ?>

                    <span style="color:green; float:right;">
                        <?php echo $row['business_name']; ?>
                    </span>
                </h1>

                    <span class="profile-badge">Active User</span>
                    
                    <div class="info-grid">
                    
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <h3>Phone Number</h3>
                                <p><?php echo $row['phone']; ?></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="info-content">
                                <h3>Join At</h3>
                                <p><?php echo !empty($row['join_at']) ? $row['join_at'] : 'Not specified'; ?></p>
                            </div>
                        </div>
                        <br>
                        <?php if(!empty($row['latitude']) && !empty($row['longitude'])) { ?>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-map-pin"></i>
                            </div>
                            <div class="info-content">
                                <h3>Location</h3>
                                <a href="map.php?latitude=<?php echo $row['latitude']; ?>&longitude=<?php echo $row['longitude']; ?>" target="_blank" class="map-link">
                                    <i class="fas fa-external-link-alt"></i> View on Map
                                </a>
                            </div>
                        </div>
                        <?php } ?>

                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-content">
                                <h3>City</h3>
                                <p><?php echo $row['area_name']; ?></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-map"></i>
                            </div>
                            <div class="info-content">
                                <h3>Address</h3>
                                <p><?php echo !empty($row['address']) ? $row['address'] : 'Not specified'; ?></p>
                            </div>
                        </div>

                    </div>
                    
                    <div class="action-buttons">
                        <a href="messenger.php?user_id=<?php echo $_GET['user_id'] ;?>" class="btn btn-primary">
                            <i class="fas fa-envelope"></i> Contact
                        </a>
                        <a href="index.php?user_id=<?php echo $_GET['user_id'] ;?>" class="btn btn-outline">
                            <i class="fas fa-home"></i> Store Home
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to info items
            const infoItems = document.querySelectorAll('.info-item');
            infoItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
            
            // Add animation to profile card on load
            const profileCard = document.querySelector('.profile-card');
            profileCard.style.opacity = '0';
            profileCard.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                profileCard.style.transition = 'all 0.5s ease';
                profileCard.style.opacity = '1';
                profileCard.style.transform = 'translateY(0)';
            }, 300);
        });
    </script>

    <?php include "footer.php"; ?>


<?php
include "header.php";
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

// Fetch current user data
$sql = "SELECT * FROM users WHERE id = {$_SESSION['user_id']}";
$query = mysqli_query($connect, $sql);
$row = mysqli_fetch_array($query);
?>

    <style>
        :root {
            --primary: #81c408;
            --secondary: #cce2a4;
            --success: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
            --accent: #7209b7;
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

        .account-container {
            max-width: 1200px;
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
            background: linear-gradient(135deg, var(--primary), var(--accent));
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

        .account-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }

        @media (max-width: 992px) {
            .account-content {
                grid-template-columns: 1fr;
            }
        }

        .profile-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
            height: fit-content;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .profile-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color:#fff;
        }

        .profile-img-container {
            width: 180px;
            height: 180px;
            margin: -30px auto 1.5rem;
            position: relative;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background: var(--light);
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .profile-img-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e0e0e0, #f0f0f0);
            color: var(--gray);
        }

        .profile-img-placeholder i {
            font-size: 4rem;
        }

        .profile-upload {
            padding: 0 1.5rem 1.5rem;
        }

        .profile-upload label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            
        }

        .form-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
            border:1px solid #ffb524aa;
        }

        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            background:#fff;
            color: white;
            padding: 1.5rem;
        }

        .form-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--primary);
        }

        .form-header h2 i {
            font-size: 1.25rem;
        }

        .form-body {
            padding: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label i {
            color: var(--primary);
            width: 20px;
        }

        .required::after {
            content: " *";
            color: #e63946;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            transition: var(--transition);
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .btn-save {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 10px;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            margin-top: 1rem;
        }

        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-save:active {
            transform: translateY(-1px);
        }

        .btn-save:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .response-msg {
            padding: 1rem;
            border-radius: 10px;
            margin-top: 1.5rem;
            text-align: center;
            font-weight: 500;
            display: none;
        }

        .success-msg {
            background: rgba(76, 201, 240, 0.15);
            color: #1a936f;
            border: 1px solid rgba(76, 201, 240, 0.3);
        }

        .error-msg {
            background: rgba(230, 57, 70, 0.15);
            color: #e63946;
            border: 1px solid rgba(230, 57, 70, 0.3);
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }

        .input-with-icon .form-control {
            padding-left: 3rem;
        }

        .location-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e9ecef, transparent);
            margin: 1.5rem 0;
        }

        .password-note {
            font-size: 0.875rem;
            color: var(--gray);
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="account-container">
        <div class="page-header">

        </div>

        <div class="account-content">
            <!-- Profile Picture Section -->
            <div class="profile-card">
                <div class="profile-header">
                    <h2>Profile Picture</h2>
                </div>
                <div class="profile-img-container">
                    <?php if (!empty($row['img'])) { ?>
                        <img src="uploads/<?php echo $row['img']; ?>" class="profile-img" alt="Profile Picture">
                    <?php } else { ?>
                        <div class="profile-img-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php } ?>
                </div>
                <div class="profile-upload">
                    <label for="img-upload"><i class="fas fa-camera"></i> Update Profile Picture</label>
                    <input id="img-upload" name="img" type="file" class="form-control" accept="image/*">
                </div>
            </div>

            <!-- Account Info Form -->
            <div class="form-card">
                <div class="form-header">
                    <h2><i class="fas fa-user-edit"></i> Personal Information</h2>
                </div>
                <div class="form-body">
                    <div class="form-grid">
                        <!-- Name Fields -->
                        <div class="form-group">
                            <label class="form-label required">
                                <i class="fas fa-user"></i> Full Name
                            </label>
                            <input name="fullname" type="text" class="form-control" value="<?php echo $row['fullname']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">
                                <i class="fas fa-signature"></i> Business Name
                            </label>
                            <input name="business_name" type="text" class="form-control" value="<?php echo $row['business_name']; ?>" required>
                        </div>

                        <!-- City Selection -->
                        <div class="form-group full-width">
                            <label class="form-label required">
                                <i class="fas fa-map-marker-alt"></i> Area
                            </label>
                            <select name="area_id" class="form-control" required>
                                <option value="">Select Area</option>
                                <?php
                                $query_area = mysqli_query($connect, "SELECT * FROM area ORDER BY id DESC");
                                while ($row_area = mysqli_fetch_array($query_area)) {
                                    $selected = ($row['area_id'] == $row_area['id']) ? "selected" : "";
                                    echo "<option value='{$row_area['id']}' $selected>{$row_area['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Address -->
                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-home"></i> Address
                            </label>
                            <input name="address" type="text" class="form-control" placeholder="Street, Building, Apartment" value="<?php echo $row['address']; ?>">
                        </div>

                        <!-- Location -->
                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-globe-americas"></i> Location Coordinates
                            </label>
                            <div class="location-fields">
                                <div class="input-with-icon">
                                    <i class="fas fa-latitude"></i>
                                    <input name="latitude" type="text" class="form-control" placeholder="Latitude" value="<?php echo $row['latitude']; ?>">
                                </div>
                                <div class="input-with-icon">
                                    <i class="fas fa-longitude"></i>
                                    <input name="longitude" type="text" class="form-control" placeholder="Longitude" value="<?php echo $row['longitude']; ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="form-group full-width">
                            <label class="form-label required">
                                <i class="fas fa-phone"></i> Phone Number
                            </label>
                            <div class="input-with-icon">
                                <i class="fas fa-mobile-alt"></i>
                                <input name="phone" type="tel" class="form-control" value="<?php echo $row['phone']; ?>" required>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <!-- Password -->
                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <div class="input-with-icon">
                                <i class="fas fa-key"></i>
                                <input name="password" type="password" class="form-control" autocomplete="new-password" placeholder="Leave blank to keep current password">
                            </div>
                            <p class="password-note">Enter a new password only if you want to change it</p>
                        </div>
                    </div>
                    <div class="form-item mt-5">

<style>

.credit-card-box {
    background: linear-gradient(135deg, #1e272e, #485460);
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    color: white;
    transition: 0.3s;
}

.credit-card-box:hover {
    transform: scale(1.02);
}

.card-front label {
    color: #dcdde1;
    font-weight: 500;
    margin-bottom: 5px;
    display: block;
}

.card-input {
    background-color: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    border-radius: 10px;
}

.card-input::placeholder {
    color: #ced6e0;
}

.card-input:focus {
    border-color: #70a1ff;
    background-color: rgba(255, 255, 255, 0.2);
    box-shadow: none;
    color: white;
}
</style>



        <div class="form-item mt-5">
    <h5 class="mb-3 text-center">Bank Card Details</h5>
    <div class="credit-card-box p-4 mb-4">
        <div class="card-front">
            <div class="card-number-box">
                <label>Card Number</label>
                <input name="card_number" value="<?php echo $row['card_number'];?>" 
                       type="text" class="form-control card-input" 
                       placeholder="1234 5678 9012 3456" maxlength="19">
            </div>

            <div class="card-holder-box mt-3">
                <label>Cardholder Name</label>
                <input name="card_name" value="<?php echo $row['card_name'];?>" 
                       type="text" class="form-control card-input" 
                       placeholder="Full Name">
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label>Expiry Date</label>
                    <input name="card_expiry" value="<?php echo $row['card_expiry'];?>" 
                           type="text" class="form-control card-input" 
                           placeholder="MM/YY" maxlength="5">
                </div>

                <div class="col-md-6">
                    <label>CVV</label>
                    <input name="card_cvv" type="password" 
                           class="form-control card-input" 
                           placeholder="123" maxlength="4">
                </div>
            </div>
        </div>
    </div>
</div>
<br>

                    <!-- Save Button -->
                    <button type="button" class="btn-save" onclick="save_info(this);">
                        <i class="fas fa-save"></i> Save Changes
                    </button>

                    <!-- Response Message -->
                    <div id="response-msg" class="response-msg"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function save_info(element) {
        // Get input values
        var fullname = $("[name='fullname']").val();
        var business_name = $("[name='business_name']").val();
        var address = $("[name='address']").val();
        var latitude = $("[name='latitude']").val();
        var longitude = $("[name='longitude']").val();
        var phone = $("[name='phone']").val();
        var area_id = $("[name='area_id'] option:selected").val();
        var password = $("[name='password']").val();
  var card_number = $("[name='card_number']").val();
  var card_name = $("[name='card_name']").val();
  var card_expiry = $("[name='card_expiry']").val();
  var card_cvv = $("[name='card_cvv']").val();
  
  
    // Validate card number: must be exactly 16 digits
    if (!/^\d{16}$/.test(card_number)) {
      alert("Card number must be 16 digits without spaces or separators.");
      return false;
    }

    // Validate cardholder name
    if (card_name.length < 3) {
      alert("Please enter a valid cardholder name.");
      return false;
    }

    // Validate expiry date in MM/YY format
    if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(card_expiry)) {
      alert("Please enter a valid expiry date in the format MM/YY, e.g., 12/25.");
      return false;
    }

    // Validate CVV (3 or 4 digits)
    if (!/^\d{3,4}$/.test(card_cvv)) {
      alert("CVV must be 3 or 4 digits.");
      return false;
    }

  
        // Validation
        if (!phone || phone.length < 9) {
            alert('Please enter a valid phone number');
            return;
        }
        if (!fullname || !business_name || fullname.length < 3 || business_name.length < 3) {
            alert('Please enter valid first and last names');
            return;
        }
        if (!area_id) {
            alert('Please select a city');
            return;
        }

        // FormData
        var formData = new FormData();
        formData.append('fullname', fullname);
        formData.append('business_name', business_name);
        formData.append('address', address);
        formData.append('latitude', latitude);
        formData.append('longitude', longitude);
        formData.append('phone', phone);
        formData.append('area_id', area_id);
        formData.append('password', password);
          formData.append('card_number', card_number);
          formData.append('card_name', card_name);
          formData.append('card_expiry', card_expiry);
          formData.append('card_cvv', card_cvv);

        if ($("[name='img']")[0].files[0]) {
            formData.append('img', $("[name='img']")[0].files[0]);
        }

        // Show loading state
        $(element).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        $(element).prop('disabled', true);

        // Hide previous messages
        $("#response-msg").hide();

        // AJAX request
        $.ajax({
            type: "POST",
            url: "my_account_backDB.php",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                response = JSON.parse(response.trim());
                if (response.status === "ok") {
                    $("#response-msg").removeClass('error-msg').addClass('success-msg').text(response.contentMsg).show();
                    setTimeout(function() { location.reload(); }, 2000);
                } else if (response.status === "error") {
                    $("#response-msg").removeClass('success-msg').addClass('error-msg').text(response.contentMsg).show();
                }
                
                // Reset button state
                $(element).html('<i class="fas fa-save"></i> Save Changes');
                $(element).prop('disabled', false);
            },
            error: function() {
                $("#response-msg").removeClass('success-msg').addClass('error-msg').text('An error occurred while saving data').show();
                
                // Reset button state
                $(element).html('<i class="fas fa-save"></i> Save Changes');
                $(element).prop('disabled', false);
            }
        });
    }

    // Add some interactive effects
    $(document).ready(function() {
        // Add focus effect to form controls
        $('.form-control').on('focus', function() {
            $(this).parent().css('transform', 'translateY(-2px)');
        }).on('blur', function() {
            $(this).parent().css('transform', 'translateY(0)');
        });

        // Profile image preview
        $('input[name="img"]').on('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    $('.profile-img-container').html('<img src="' + e.target.result + '" class="profile-img" alt="Profile Picture">');
                }
                
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    });
    </script>
</div>
    <?php include "footer.php"; ?>



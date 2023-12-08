<?php
session_start();
$selectedProducts = json_decode($_GET['selected_products'], true);
$selectedPayment = isset($_GET['selected_payment']) ? $_GET['selected_payment'] : '';
$_SESSION['selected_products'] = $selectedProducts;
$_SESSION['selected_payment'] = $selectedPayment;


if (!isset($_SESSION["user_id"])) {
    header('Location: ../../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
include '../../users/php/dbhelper.php'; 
$pdo = dbconnect();

$users = get_record("users", "user_id", $user_id);
$user_role = $users['role'];

// Retrieve the shop ID from the query string
if (isset($_GET['shop_id'])) {
    $selectedShopId = $_GET['shop_id'];
} else {
    $selectedShopId = "BulakBuy"; // Default value
}
if (isset($_GET['total_sales'])) {
    $totalSales = $_GET['total_sales'];
} else {
    $totalSales = 0; // Default value
}
// Function to retrieve the shop name based on the shop ID
function getShopName($shopId, $pdo) {
    $sql = "SELECT shop_name FROM shops WHERE shop_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$shopId]);
    $shop = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($shop) {
        return $shop['shop_name'];
    } else {
        return "Shop Name Not Found"; // Provide a default value or handle accordingly
    }
}

// Function to retrieve the shop phone based on the shop ID
function getShopPhone($shopId, $pdo) {
    $sql = "SELECT shop_phone FROM shops WHERE shop_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$shopId]);
    $shop = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($shop) {
        return $shop['shop_phone'];
    } else {
        return "Shop Phone Not Found"; // Provide a default value or handle accordingly
    }
}

$selectedShopName = getShopName($selectedShopId, $pdo);
$selectedShopPhone = getShopPhone($selectedShopId, $pdo);
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Payment</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="../css/login.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" /> 
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity= "sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
            

        <style>
             body{
            background-color:#f5f5f5;
        }
        .container{
            width:600px;
            margin:auto;
            margin-top: 60px;
            font-family: 'Poppins';
            background-color: white;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding:20px;
            padding-bottom:20px;
        }

        .row img{
            margin:auto;
            width:230px;
            height:90px;
        }
        .billing, .bulakbuy{
            color:#666;
            font-size: 17px;
            text-align: center;
             letter-spacing: 0.1rem;
            margin-top: 25px;
            font-weight: 500;
        }
        .bulakbuy{
            color:#666;
            font-size: 14px;
            text-align: center;
            letter-spacing: 0.1rem;
            margin-top: 15px;
            margin-left: 15px;
            font-weight: 500;
        }
        .needs-validation{
            margin-top:40px;
        }
        label {
            font-size: 15px;
            color:#666;
        }
        .form-label {
            font-size: 15px;
            color:#666;
            margin-left:-10px;
            margin-top: 15px;
        }

        .form-control {
            /* Add general styling for form controls here */
            padding: 10px;
            border:none;
            width: 265px;
            background-color: #F5F5F5;
            border-radius:10px;
            color:#888;
            margin-top: 10px;
            outline: none !important;
        }
        
       
        .form-control:focus {
            border:1px solid #fefefe;
            outline:none;
        }
        .custom-font-size {
            font-size: 14px; /* Adjust the font size as needed */
            color:#666;
        }
        .form-control1 {
            /* Add general styling for form controls here */
            padding: 10px;
            border:none;
            width: 560px;
            background-color: #F5F5F5;
            border-radius:10px;
            color:#888;
            margin-top: 15px;
            margin-left: -15px;
            outline: none !important;
        }
       
        .form-control1:focus {
            border:1px solid #fefefe;
            outline:none;
        }
        .btn{
            background-color: #65A5A5;
            color:white;
            width:560px;
            padding:7px;
            border-radius:10px;
            margin-top: 30px;
             letter-spacing: 0.1rem;
            font-size: 16px;
        }
        .btn:hover{
            color:#fefefe;
        }
        .password-input-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 45%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        /* Eye icon styles */
        .toggle-password i {
            font-size: 18px;
            color: #999;
        }

        /* Style the eye icon when password is revealed */
        .password-revealed i {
            color: #33b5e5;
        }
        .button{
            margin-top: -20px;
        }
        .container1{
            margin-top: -10px;
        }
        .container1 .row a{
            color:#888;
            font-size: 14px;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
        }
        .error-text{
            color: #721c24;
            padding: 8px 10px;
            text-align: center;
            border-radius: 5px;
            background: #f8d7da;
            font-size: 15px;
            border: 1px solid #f5c6cb;
            margin-bottom: 20px;
            display: none;
            font-weight: 300;
        }
        @media (max-width: 768px) {
            body{
                background-color:#f5f5f5;
            
            }
            .container{
                width: 95%;
                    margin: 20px auto;
                    background-color: #fff;
                    border-radius: 5px;
                    
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        
            }

            .row img{
                margin:auto;
                width:200px;
                height:80px;
            }
            .billing{
                color:#666;
                font-size: 15px;
                text-align: center;
                letter-spacing: 0.1rem;
                margin-top: 10px;
                font-weight: 500;
            }
            .bulakbuy{
            color:#666;
            font-size: 13px;
            text-align: center;
            letter-spacing: 0.1rem;
            margin-top: 15px;
            margin-left: 15px;
            font-weight: 500;
        }
            .form-control {
                /* Add general styling for form controls here */
                padding: 20px;
                border:none;
                width: 310px;
                background-color: #F5F5F5;
                border-radius:10px;
                color:#888;
                margin-top: 10px;
                outline: none !important;
                font-size: 13px;
                border:none;
                margin-left: -5px;
            }
            label {
            font-size: 13px;
            color:#666;
        }
        .form-label {
            font-size: 13px;
            color:#666;
            margin-left:-10px;
            margin-top: 15px;
        }

            .form-control::placeholder {
                font-size: 13px;
                color:#A0A0A0;
            }
            .form-control:focus {
                border:1px solid #fefefe;
                outline:none;
            }
            .custom-font-size {
            font-size: 13px; /* Adjust the font size as needed */
            color:#666;
        }
        .form-control1 {
            /* Add general styling for form controls here */
            padding: 10px;
            border:none;
            width: 310px;
            background-color: #F5F5F5;
            border-radius:10px;
            color:#888;
            margin-top: 10px;
            margin-left: -18px;
            outline: none !important;
        }
       
        .form-control1:focus {
            border:1px solid #fefefe;
            outline:none;
        }
            .button{
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .btn{
                background-color: #65A5A5;
                color:white;
                width:310px;
                padding:7px;
                border-radius:10px;
                margin-top: 30px;
                letter-spacing: 0.1rem;
                font-size: 13px;
            }
            .btn:hover{
                color:#fefefe;
            }
            .password-input-container {
                position: relative;
            }

            .toggle-password {
                position: absolute;
                top: 45%;
                right: 10px;
                transform: translateY(-50%);
                cursor: pointer;
            }

            /* Eye icon styles */
            .toggle-password i {
                font-size: 18px;
                color: #999;
            }

            /* Style the eye icon when password is revealed */
            .password-revealed i {
                color: #33b5e5;
            }
            .button{
                margin-top: -20px;
            }
            .container1{
                margin-top: -10px;
            }
            .container1 .row a{
                color:#888;
                font-size: 13px;
                text-align: center;
                text-decoration: none;
                margin-top: 10px;
            }
            .error-text{
                color: #721c24;
                padding: 8px 10px;
                text-align: center;
                border-radius: 5px;
                background: #f8d7da;
                font-size: 13px;
                border: 1px solid #f5c6cb;
                margin-bottom: 25px;
                display: none;
                font-weight: 300;
            }
            .two {
                display: flex; /* Use flexbox for horizontal alignment */
                flex-wrap: wrap; /* Allow flex items to wrap to the next line if needed */
               
            }

            .form-group {
                flex: 1; /* Each form-group takes equal space */
            }
        
            
        }
        
   
           
        </style>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12 order-md-1">
                    <h4 class="billing mb-3">Billing address</h4>
                    <?php if (!empty($users)): ?>
                    <form class="needs-validation" method="POST" action="post.php" enctype="multipart/form-data">
                    <div class="two">
                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName">First name</label>
                                    <input type="text" class="form-control custom-font-size" name="first_name" value="<?php echo $users['first_name']; ?>" >
                                    <div class="invalid-feedback">Valid first name is required.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName">Last name</label>
                                    <input type="text" class="form-control custom-font-size" name="last_name" value="<?php echo $users['last_name']; ?>">
                                    <div class="invalid-feedback">Valid last name is required.</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    <label for="mobile">Mobile</label>
                                    <input type="number" class="form-control custom-font-size" name="phone" value="<?php echo $users['phone']; ?>">
                                    <div class="invalid-feedback">Please enter a valid mobile number for shipping updates.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control custom-font-size" name="email" value="<?php echo $users['email']; ?>">
                                    <div class="invalid-feedback">Please enter a valid email address for shipping updates.</div>
                                </div>
                            </div>
                    </div>
                            <div class=" col-md-6 mb-3">
                                <label for="merchantname" class="form-label">Merchant Name</label>
                                <input type="text" class="form-control1 custom-font-size" name="merchantname" value="<?php echo $selectedShopName; ?>">
                            </div> 
                            <div class="col-md-6 mb-3">
                                <label for="merchantphone"  class="form-label">Merchant Phone</label>
                                <input type="text" class="form-control1 custom-font-size" name="merchantphone" value="<?php echo $selectedShopPhone; ?>">
                            </div>
                      
                        <div class=" col-md-6 mb-3">
                            <label for="amount-label" class="form-label">Amount</label>
                            <input type="number" class="form-control1 custom-font-size" name="amount" value="<?php echo $totalSales; ?>">
                            <div class="invalid-feedback">Please enter the amount to be paid.</div>
                        </div>
                    
                        <input type="hidden" name="selected_products" value="<?php echo htmlspecialchars(json_encode($selectedProducts), ENT_QUOTES, 'UTF-8'); ?>">
                        <button class="btn btn-lg btn-block" id= "checkoutForm" type="submit">Continue to checkout</button>
                
                    </form>
                    <?php else: ?>
                        <p>User not found.</p>
                    <?php endif; ?>
                    </div>
                    </div>
                    <footer class="my-3 pt-3 text-muted text-center text-small">
                        <p class="bulakbuy mb-1 ">© <?php echo date("Y"); ?> BulakBuy.ph</p>
                    </footer>
                </div>
                <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
                    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
                    crossorigin="anonymous"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
                    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
                    crossorigin="anonymous"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
                    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
                    crossorigin="anonymous"></script>
               

    </body>

    </html>

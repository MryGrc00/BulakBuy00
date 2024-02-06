<?php 
session_start(); 
include '../../users/php/dbhelper.php'; // Adjust the path as needed
?><!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <title>Payment</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
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
                margin: 170px auto;
                background-color: #fff;
                border-radius: 5px;
                
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        
            }
            .payment{
            color:#666;
            font-size: 13px;
            text-align: center;
             letter-spacing: 0.1rem;
            margin-top: 30px;
            font-weight: 500;
            margin-left:10px;
        }
        .bi-check-circle{
          font-size: 60px;
          color:#65A5A5;
          margin-right:10px;
          
         
         
      }
      .center-icon {
        text-align: center;
    }

    .center-icon i {
        margin: auto;
    }
      .reference{
            color:#666;
            font-size: 13px;
            text-align: center;
             letter-spacing: 0.1rem;
             line-height: 2;
            margin-top: 30px;
            font-weight: 500;
            margin-left:10px;
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
                margin-bottom: 30px;
                letter-spacing: 0.1rem;
                font-size: 13px;
            }
            .btn:hover{
                color:#fefefe;
            }
           
            .button{
                margin-top: -20px;
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

<?php


date_default_timezone_set('Asia/Manila');

if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    $pdo = dbconnect();
    $user_id = $_SESSION['user_id'];
    $userRole = $_SESSION['role'];

    // Get the shop_id for the logged-in user
    $sql = "SELECT shop_id FROM shops WHERE owner_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($result && isset($result['shop_id'])) {
        $shop_id = $result['shop_id'];
    
        // Set start and end dates for the subscription
        $s_date = date('Y-m-d H:i:s'); 
        $e_date = date('Y-m-d H:i:s', strtotime('+1 month')); // 1 month from today
    
        // Check if a subscription already exists for the shop_id
        $checkSql = "SELECT * FROM subscription WHERE shop_id = :shop_id";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindParam(':shop_id', $shop_id, PDO::PARAM_INT);
        $checkStmt->execute();
        $existingSub = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
        if ($existingSub) {
            // Update the existing subscription record
            $updateSql = "UPDATE subscription SET s_date = :s_date, e_date = :e_date, status = 'active' WHERE shop_id = :shop_id";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->bindParam(':s_date', $s_date);
            $updateStmt->bindParam(':e_date', $e_date);
            $updateStmt->bindParam(':shop_id', $shop_id, PDO::PARAM_INT);
            $updateStmt->execute();
        } else {
            // Insert a new subscription record
            $insertSql = "INSERT INTO subscription (shop_id, s_date, e_date, status) VALUES (:shop_id, :s_date, :e_date, 'active')";
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->bindParam(':shop_id', $shop_id, PDO::PARAM_INT);
            $insertStmt->bindParam(':s_date', $s_date);
            $insertStmt->bindParam(':e_date', $e_date);
            $insertStmt->execute();
        }

        if (isset($_GET['paymongo_id'])) {
            $paymongo_id = $_GET['paymongo_id'];
        }

        // Fetch user role and decide the redirection URL
        $backUrl = '';
        if ($userRole === 'arranger') {
            $backUrl = '../../users/arranger/arranger_home.php';
        } elseif ($userRole === 'seller') {
            $backUrl = '../../users/vendor/vendor_home.php';
        } else {
            $backUrl = 'index.php'; // Default redirection if the role is not arranger or vendor
        }

        // Display the success message and details
        echo "<div class='container center'>";
        echo "<div class='center-icon'>";
        echo "<i class='bi bi-check-circle'></i>";
        echo "</div>";
        echo "<h4 class='payment'>Payment Successful</h4>";
        echo "<div class='alert '>";
        echo "<p class='reference'>Reference Code: $paymongo_id</p>";
        echo "</div>";
        echo "<a class='btn  btn-lg' href='http://192.168.1.167/Bulakbuy00/Payments/Subscription/$backUrl'>Back to main</a>";
        echo "</div>";
    } else {
        // Handle case where shop_id is not found
        echo "<div class='container center'>";
        echo "<div class='alert alert-danger'>";
        echo "Shop ID not found for the user.";
        echo "</div>";
        echo "</div>";
    }
} else {
    // Handle the case where no user session is found
    echo "<div class='container center'>";
    echo "<div class='alert alert-danger'>";
    echo "No active session found. Please log in.";
    echo "</div>";
    echo "</div>";
}
?>

</body>
</html>

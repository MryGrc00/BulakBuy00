<?php
session_start();
include '../php/dbhelper.php';
include '../php/checksession.php';

$pdo = dbconnect();
$userDetails = null;
$serviceDetails = null;
$arrangerDetails = null;


if (isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :userID");
    $stmt->execute(['userID' => $userID]);
    $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userDetails) {
        $userDetails['full_name'] = $userDetails['first_name'] . " " . $userDetails['last_name'];
    } else {
        echo "User details not found.";
    }
}

if (isset($_GET['service_id'])) {
   $serviceID = $_GET['service_id'];
   $stmt = $pdo->prepare("SELECT * FROM services WHERE service_id = :serviceID");
   $stmt->execute(['serviceID' => $serviceID]);
   $serviceDetails = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($serviceDetails && isset($serviceDetails['arranger_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :arrangerID");
        $stmt->execute(['arrangerID' => $serviceDetails['arranger_id']]);
        $arrangerDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        $arrangerDetails['full_name'] = $arrangerDetails['first_name'] . " " . $arrangerDetails['last_name'];

    } else {
        echo "Service details not found or arranger not specified.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $serviceID = $_POST['service_id']; // Get service ID
   $customerID = $_SESSION['user_id']; // Get customer ID from session
   $date = $_POST['date'];
   $time = $_POST['time'];
   $totalAmount = $_POST['total_price']; // Use total_price from form data
   $packageID = $_POST['package_id']; // Use package_id from form data
   $status = "Pending";

   if (!empty($serviceID) && !empty($customerID) && !empty($date) && !empty($time) && !empty($totalAmount) && !empty($packageID)) {
       $stmt = $pdo->prepare("INSERT INTO servicedetails (service_id, customer_id, package_id, amount, date, time, status) VALUES (:serviceID, :customerID, :packageID, :totalAmount, :date, :time, :status)");
       $stmt->execute(['serviceID' => $serviceID, 'customerID' => $customerID, 'packageID' => $packageID, 'totalAmount' => $totalAmount, 'date' => $date, 'time' => $time, 'status' => $status]);
   } else {
       $errorMsg = "All fields are required.";
   }
}

?>

<!DOCTYPE html> 
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Service Request</title>
      <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
      <style>
         @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;   700&display=swap");
         * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
         }
         .navbar img {
            padding: 0;
            width: 195px;
            height: 100px;
            margin-top: -10px;
            margin-left: 186%;
         }
         .form {
            position: relative;
            color: #8e8e8e;
            left: 130px;
         }
         .form-inline .fa-search {
            position: absolute;
            top: 43px;
            left: 78%;
            color: #9ca3af;
            font-size: 22px;
         }
         .form-input[type="text"] {
            height: 50px;
            width: 500px;
            background-color: #f0f0f0;
            border-radius: 10px;
            margin-left: 430px;
            margin-top: -10px;
         }
         .back {
            display: none;
         }
         .nav-hr {
            width: 60%;
            margin: auto;
            margin-top: -6px;
         }
         #search-results {
            display: none;
         }
         .container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
         }
         .column1 {
            flex-basis: 50%;
            margin-top: 10px;
            display: flex;
            flex-direction: column;
         }
         .location {
            border-radius: 10px;
            border: 1px solid #65A5A5;
            padding: 10px;
            width: 120%;
            display: flex;
            margin-top: 20px;
         }
         .location i {
            margin-right: 10px;
            position: relative;
            font-size: 30px;
            color: #666;
            margin-top: 15px;
         }
         .location-info {
            flex-direction: column;
            font-size: 13px;
            line-height: 20%;
            margin-top: 2.5%;
            position: relative;
            margin-left: 10px;
         }
         .location-info a {
            color: #666;
            text-decoration: none;
         }
         .name {
            font-size: 13px;
         }
         .fa.fa-angle-right {
            margin-left: 10px;
            margin-top: 100px;
            color: #555;
            font-size: 16px;
         }
         .number {
            font-size: 13px;
            margin-top: 20px;
         }
         .street {
            font-size: 13px;
            margin-top: 20px;
         }
         .cart-container {
            width: 120%;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
         }
         .all-items {
            display: flex;
            background-color: #f0f0f0;
            padding: 10px;
            top: 0px;
         }
         .items-label {
            font-size: 13px;
            margin-top: 2%;
            margin-left: 20px;
            color: #555;
         }
         .payment-method {
            display: flex;
            margin-left: 20px;
            margin-top: 20px;
            margin-bottom: 15px;
         }
         .wallet {
            color: #666;
            font-size: 20px;
            margin-left: 3px;
         }
         .payment-method img {
            width: 25px;
            height: 20px;
         }
         .payment-method input[type="radio"] {
            margin-left: auto;
            margin-right: 25px;
         }
         .payment-method label {
            font-size: 14px;
            margin-left: 15px;
         }
         .datetime-container {
            display: inline-block;
            margin-right: 145px;
            padding: 20px;
         }
         .datetime-input {
            display: block;
            margin-top: 20px;
            /* Adjust the spacing between label and input */
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            width: 140px;
            color: #555;
         }
         .date-label {
            font-size: 13px;
            margin-top: 2%;
            margin-left: 2px;
            color: #555;
            display: block;
         }
         .cart-item {
            display: flex;
            align-items: center;
            
         }
         .shop-info {
            flex-direction: column;
            font-size: 13px;
            line-height: 5%;
            margin-top: 2%;
            position: relative;
         }
         .shop-info img {
            width: 35px;
            height: 35px;
            margin-top: 2px;
            margin-left: 15px;
            border-radius: 50px;
         }
         .shop-info h3 {
            margin-top: -35px;
         }
         .shop-info a h3 {
            font-size: 14px;
            margin-left: 60px;
            margin-top: -25px;
            color: #555;
         }
         .shop-name a {
            display: flex;
            align-items: center;
            flex-grow: 1;
         }
         .shop-name a:hover {
            text-decoration: none;
         }
         .fa.fa-angle-right {
            margin-left: 10px;
            margin-top: -33px;
            color: #555;
            font-size: 18px;
         }
         .item-checkbox {
            margin-right: 15px;
         }
         .custom-checkbox {
            margin-top: -5px;
            ;
         }
         .cart-item img {
            max-width: 100px;
            height: 110px;
            margin-right: 20px;
            margin-top: 10px;
         }
         .item-details h2 {
            font-size: 16px;
            color: #555;
         }
         .service-location {
            font-size: 13px;
            color: #777;
         }
         .shop-num {
            font-size: 13px;
            color: #777;
         }
         .color {
            font-size: 13px;
            color: #666;
         }
         .price {
            color: #ff7e95;
            font-weight: 500;
            font-size: 13px;
         }
         .quantity-control {
            display: flex;
            position: absolute;
            margin-top: -31px;
            left: 51.7%;
            transform: translateX(-50%);
         }
         input[type="text"] {
            width: 50px;
            text-align: center;
            font-size: 14px;
            padding: 1px;
            border: 1px solid #d2cfcf;
            color: #666;
         }
         .quantity-button {
            background-color: transparent;
            color: #666;
            border: 1px solid #d2cfcf;
            cursor: pointer;
            padding: 1px 10px;
            font-size: 13px;
            border-radius: 2px;
         }
         .quantity-button:focus {
            outline: none;
         }
         .cart-hr {
            margin-top: 5px;
         }
         .column2 {
            flex-basis: 37%;
            margin-top: 21px;
         }
         .border {
            height: 8px;
            background-color: #f0f0f0;
         }
         .summary-container {
            width: 100%;
            margin-top: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
         }
         .order-summary {
            display: flex;
            background-color: #f0f0f0;
            padding: 10px;
            top: 0px;
         }
         .order-label {
            margin-top: 5px;
            margin-left: 10px;
         }
         .summary-items {
            padding: 20px;
         }
         .product-price, .total-payment, .service-hours {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
         }
         .total {
            font-weight: 600;
         }
         .product, .total, .hours-label {
            flex: 1;
         }
         .order-price, .product, .service-hours, .hours {
            font-size: 14px;
         }
         .t-payment, .total {
            font-size: 13px;
         }
         .order-price, .t-payment, .hours {
            flex: 1;
            text-align: right;
         }
         .total-item {
            display: none;
         }
         .total-price1 {
            display: none;
         }

         .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
         }
         .checkout a {
            color: #fff;
            text-decoration: none;
         }
         .checkout {
            background-color: #65A5A5;
            color: white;
            border: none;
            outline: none;
            padding: 10px;
            padding-left: 140px;
            padding-right: 140px;
            width: 100%;
            border-radius: 20px;
            margin-bottom: 20px;
            text-align: center;
         }
         .checkout:focus {
            outline: none;
            border: none;
         }
         /* Style for modals */

         .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
         }
         .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            text-align: center;
         }
         .confirm-order {
            font-size: 19px;
            color: #444;
            margin-top: 20px;
         }
         .confirm-note {
            font-size: 14px;
            color: #666;
         }
         .confirm-btn {
            margin-top: 25px;
         }
         .confirm {
            border: none;
            outline: none;
            font-size: 14px;
            padding: 8px 20px;
            background-color: #65A5A5;
            color: white;
            border-radius: 20px;
         }
         .cancel {
            border: 1px solid #b0b0b0;
            border-radius: 20px;
            padding: 8px 20px;
            font-size: 14px;
            text-align: left;
            background-color: white;
            margin-right: 100px;
         }
         .confirm:focus {
            outline: none;
            border: none;
         }
         .cancel:focus {
            outline: none;
            border: none;
         }
         .bi-check-circle {
            font-size: 70px;
            color: #65A5A5;
            margin: auto;
            margin-top: 5%;
         }
         .confirmed {
            font-size: 20px;
            margin-top: 10px;
            font-weight: 500;
            color: #555;
         }
         .sucessful {
            font-size: 13px;
            margin-top: 15px;
            font-weight: 500;
            color: #666;
            padding: 0px 15px
         }
         .check-status {
            font-size: 13px;
            margin-top: 5px;
            font-weight: 500;
            color: #666;
         }
         .close {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 25px;
            cursor: pointer;
         }
         .sucessful a {
            color: #ff7e95;
         }
         .sucessful a:hover {
            color: #ff7e95;
            text-decoration: none;
         }
         .c-shopping {
            border: none;
            outline: none;
            font-size: 13px;
            padding: 8px 20px;
            background-color: #65A5A5;
            color: white;
            border-radius: 20px;
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 10px 50px;
         }
         .c-shopping:focus {
            outline: none;
            border: none;
         }
         /*Responsiveness*/

         @media (max-width: 768px) {
            .navbar {
               position: fixed;
               background-color: white;
               width: 100%;
               z-index: 1;
            }
            .navbar img {
               display: none;
            }
            .form-input[type="text"] {
               display: none;
            }
            .nav-hr {
               width: 100%;
            }
            a #search-results{
               display: block ;
               font-size: 15px;
               margin-left: 20px;
               color: #555;
               margin-top: -20px;
            }
            a:hover{
               text-decoration: none;
               outline: none;
               border:none;
            }
            .back {
               display: block;
               font-size: 20px;
            }
            .form-inline .fa-search {
               display: none;
            }
            .form-inline .back {
               text-decoration: none;
               color: #666;
            }
            .form-inline .fa-angle-left:focus {
               text-decoration: none;
               outline: none;
            }
            .container {
               margin-top: 51px;
               display: flex;
               flex-direction: column;
               gap: 20px;
            }
            .column1 {
               display: flex;
               flex-direction: column;
               margin: 0;
               margin-left: 0;
               margin-bottom: 40px;
            }
            .location{
               border-radius: 10px;
               border:1px solid #65A5A5;
               padding:10px;
               width:100%;
               display: flex;
               margin-top: 20px;
               text-align: left;
            }
            .location i{
               margin-right: 10px;
               margin-top:5px;
               position: relative;
               font-size:25px;
               color:#666;
            }
            .location-info {

               margin-top:-15px;
               position: relative;
               flex-direction: row; /* Align items horizontally for larger screens */

            
            }
            .number{
                    font-size: 12px;
                    margin-top: 25px;
                   
                    
                }
                .name{
                    font-size: 12px;
                    margin-top: 25px;
                    
                    
                }
                .street{
                    font-size: 12px;
                    margin-top: 25px;
                    
                    
                }
            .cart-container {
               width: 100%;
               margin: 20px auto;
               background-color: #fff;
               border-radius: 5px;
               box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            }
            .all-items {
               display: flex;
               background-color: #f0f0f0;
               padding: 10px;
               top: 0px;
            }
            .items-label {
               font-size: 13px;
               margin-top: 2%;
               margin-left: 10px;
               color: #555;
            }
            .payment-method {
               display: flex;
               margin-left: 20px;
               margin-top: 20px;
               margin-bottom: 35px;
            }
            .wallet {
               color: #666;
               font-size: 13px;
               margin-left: 3px;
            }
            .payment-method img {
               width: 20px;
               height: 15px;
            }
            .payment-method input[type="radio"] {
               margin-left: auto;
               margin-right: 25px;
            }
            .payment-method label {
               font-size: 13px;
               margin-left: 15px;
            }
            .datetime-container {
               display: inline-block;
               margin-right: 10px;
               padding: 20px;
            }
            .datetime-input {
               display: block;
               margin-top: 20px;
               /* Adjust the spacing between label and input */
               padding: 5px;
               border: 1px solid #ccc;
               border-radius: 4px;
               font-size: 13px;
               width: 120px;
               color: #555;
               margin-top: 1px;
               margin-bottom: 20px;
            }
            .date-label {
               font-size: 13px;
               margin-top: 2%;
               margin-left: 2px;
               color: #555;
               display: flex;
            }
            .arranger {
               font-size: 13px;
               margin-top: 5%;
               margin-left: 20px;
               color: #555;
               display: flex;
            }
            .cart-item {
               margin-top: 20px;
               margin-left: 10px;
            }

            .shop-info {
               flex-direction: column;
               text-align: left;
               margin-left: 5px;
               line-height: 5%;
               margin-top: 2%;
               position: relative;
            }
            .shop-info img {
               width: 25px;
               height: 25px;
               margin-top: 5px;
               margin-left: 10px;
               border-radius: 50px;
            }
            .shop-info a h3 {
               font-size: 12px;
               margin-left: 45px;
               margin-top: -20px;
               color: #555;
            }
            .shop-name a {
               display: flex;
               align-items: center;
               flex-grow: 1;
            }
            .fa.fa-angle-right {
               margin-left: 10px;
               margin-top: -29px;
               color: #555;
               font-size: 16px;
            }
            .cart-hr {
               margin-top: 5px;
            }
            .custom-checkbox {
               width: 50px;
               display: flex;
               margin-top: -20px;
            }

            .item-checkbox {
               margin-right: -1px;
               margin-top: 0px;
            }
            .cart-item img {
               width: 80px;
               height: 85px;
               max-height: 100px;
               margin-right: 20px;
               margin-top: 5px;
               margin-left: 11px;
            }

            .item-details{
               margin-top:-15px;
               margin-left:5px;
               text-align: left;
            }
            .item-details h2{
               font-size: 12px;
               color:#555;
               margin-left:50px;
               margin-top:15px;
               margin-bottom:10px;
               width:170px;
               white-space: nowrap;
               overflow: hidden;
               text-overflow: ellipsis;
               position: sticky;
          
               
      
            }
            .item-details p {
               color:#555;
               margin-left:50px;
               margin-top:5px;
               width:170px;
               white-space: nowrap;
               overflow: hidden;
               text-overflow: ellipsis;
               position: sticky;
            }
           
            .price {
               color: #ff7e95;
               font-weight: 500;
               font-size: 13px;
               margin-left:30px;
               margin-top:-10px;

            }
            input[type="text"] {
               width: 40px;
               height: 20px;
               text-align: center;
               font-size: 11px;
               padding: 1px;
               border: 1px solid #d2cfcf;
               color: #666;
            }
            .quantity-button {
               background-color: transparent;
               color: #666;
               height: 20px;
               border: 1px solid #d2cfcf;
               cursor: pointer;
               padding: 1px 7px;
               font-size: 12px;
               border-radius: 2px;
            }
            .quantity-button:focus {
               outline: none;
            }
            .column2 {
               flex-basis: 37%;
               margin-top: 21px;
            }
            .border {
               height: 8px;
               margin-top: -20px;
               background-color: #f0f0f0;
            }
            .summary-container {
               width: 100%;
               margin-top: -80px;
               background-color: #fff;
               border-radius: 5px;
               margin-bottom: 150px;
               box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            }
            .order-summary {
               display: flex;
               background-color: #f0f0f0;
               padding: 10px;
            }
            .order-label {
               margin-top: 5px;
               margin-left: 10px;
               font-size: 14px;
            }
            .summary-items {
               padding: 20px;
            }
            .product-price, .total-payment {
               display: flex;
               justify-content: space-between;
               align-items: center;
               margin-bottom: 10px;
            }
            .total {
               font-weight: 600;
            }
            .product, .total {
               flex: 1;
            }
            .order-price, .product {
               font-size: 13px;
            }
            .t-payment, .total {
               font-size: 14px;
            }
            .order-price, .t-payment {
               flex: 1;
               text-align: right;
            }
            .total-item {
               display: block;
            }
            .total-price1 {
               display: block;
            }
            .button-container {
               display: flex;
               flex-direction: column;
               position: fixed;
               bottom: 0;
               left: 0;
               width: 100%;
               background-color: #fff;
               padding: 10px 0;
               box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.2);
            }
            .total-info {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    width: 100%;
                  
                    margin-top:5px;
                    margin-left:-20px
                }
                .total-item {
                    font-size: 12px;
                    color: #333;
                    margin-left: 30px;
                    white-space: nowrap;
                   
                }
                .total-price {
                    font-size: 13px;
                    color: #333;
                    white-space: nowrap;
                    display:block;
                    text-align: right;
                    margin-right: 5px;
                }
                .checkout {
                    background-color: #65A5A5;
                    color:white;
                    border:none;
                    outline:none;
                    padding-left: 50px;
                    padding-right: 50px;
                    width:93%;
                    border-radius: 10px;
                    font-size: 13px;
                    margin:auto;
                }
            .checkout a {
               color: white;
               text-decoration: none;
            }
                       /* Style for modals */
                       .modal {
                    display: none;
                    position: fixed;
                    z-index: 999; /* Make the modal appear above the navbar */
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    overflow: auto;
                    background-color: rgba(0, 0, 0, 0.5);
                }

                .modal-content {
                    background-color: #fff;
                    margin: 60% auto;
                    padding: 15px;
                    border: 1px solid #888;
                    border-radius: 10px;
                    width: 90%;
                    max-width: 400px;
                    text-align: center;
                }
                .confirm-order{
                    font-size: 13px;
                    color:#444;
                    margin-top:15px;
                }
                .confirm-note{
                    font-size: 12px;
                    color:#666;
                }
                .confirm-btn{
                    margin-top:20px;
                    margin-bottom:10px;
                }
                .confirm{
                    border: none;
                    outline: none;
                    font-size: 12px;
                    padding:6px 20px;
                    background-color: #65A5A5;
                    color:white;
                    border-radius:20px;
                }
                .cancel{
                    border: 1px solid #b0b0b0;
                    border-radius:20px;
                    padding:6px 20px;
                    font-size: 12px;
                    text-align: left;
                    background-color: white;
                    margin-right:90px;
                }
                .confirm:focus{
                    outline:none;
                    border:none;
                }
                .cancel:focus{
                    outline:none;
                    border:none;
                }
                .bi-check-circle{
                    font-size: 35px;
                    color:#65A5A5;
                    margin:auto;
                    margin-top:5%;
                }
                .confirmed{
                    font-size: 14px;
                    margin-top:10px;
                    font-weight: 500;
                    color:#666;
                }
                .sucessful{
                    font-size: 12px;
                    margin-top:5px;
                    font-weight: 500;
                    color:#666;
                    padding:0px 15px 
                }
                .check-status{
                    font-size: 13px;
                    margin-top:-10px;
                    font-weight: 500;
                    color:#666;
                }
                .close {
                    position: absolute;
                    right: 20px;
                    top: 15px;
                    font-size: 20px;
                    cursor: pointer;
                }
                .sucessful a{
                    color:#ff7e95;
                    margin-left:25px;
                }
                .sucessful a:hover{
                    color:#ff7e95;
                    text-decoration: none;
                }
                .c-shopping{
                    border: none;
                    outline: none;
                    font-size: 13px;
                    padding:6px 20px;
                    background-color: #65A5A5;
                    color:white;
                    border-radius:20px;
                    margin-top:10px;
                    margin-bottom:20px;
                    padding:10px 50px;
                }
                .c-shopping:focus{
                    outline:none;
                    border:none;
                }
            .sub-price {
                    margin-left:0px;
                    margin-top:0px;
                    font-size:12px;
                    white-space: nowrap;
                    color:#666;
                    text
                }
            /* Media query to adjust alignment for smaller screens */
            @media (max-width: 768px) {
               .column1 {
                  text-align: center;
               }
               .container {
                  flex-direction: column;
               }
            }
         }
      </style>
   </head>
   <body>
      <header>
         <nav class="navbar navbar-expand-lg">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="customer_home.php">
                    <img src="../php/images/logo.png" alt="BulakBuy Logo" class="img-fluid logo">
                </a>
            <!-- Search Bar -->
            <div class="navbar-collapse justify-content-md-center">
               <ul class="navbar-nav dib">
                  <li class="nav-item">
                     <form class="form-inline my-2 my-lg-0">
                        <a href=""><i class="fa fa-search"></i></a>
                        <input type="text"  class="form-control form-input" placeholder="Search" style="text-align:left;padding-left: 15px;font-size: 16px;">
                        <a href="javascript:void(0);" onclick="goBack()">
                           <i class="back fa fa-angle-left" aria-hidden="true"></i>
                           <div id="search-results">Service Request</div>
                         </a>
                     </form>
                  </li>
               </ul>
            </div>
         </nav>
         <hr class="nav-hr">
      </header>
      <main class="main">
         <div class="container">
         <?php if (!empty($errorMsg)): ?>
            <p class="error" style="color: red;"><?php echo $errorMsg; ?></p>
        <?php endif; ?>
            <div class="column1">
            <div class="location">
               <i class="bi bi-geo-alt"></i>
               <div class="location-info">
                     <p class="name"><?php echo $userDetails['full_name']; ?></p>
                     <p class="number"><?php echo $userDetails['phone']; ?></p>
                     <p class="street"><?php echo $userDetails['address']; ?></p>
                  </div>
            </div>

          
               <div class="cart-container">
                  
               <form id="service-request-form" action="" method="post">
                  <input type="hidden" id="package_id_input" name="package_id" value="">

                  <input type="hidden" name="service_id" value="<?php echo $serviceID; ?>"> 
                  <div class="datetime-container">
                     <label class="date-label" for="date-input">Schedule</label>
                     <input type="date" id="date-input" name="date" class="datetime-input" required />
                  </div>
                  <div class="datetime-container">
                     <label class="date-label"  for="time-input">Time</label>
                     <input type="time" id="time-input" name="time" class="datetime-input" required />
                  </div>                
                  <div class="border"></div>
                  <label class="arranger">Arranger</label>
                  <div class="cart-item">
                     
                     <div class="custom-checkbox" style="margin-top:-25px">
                        <img src="<?php echo $arrangerDetails['profile_img']; ?>" alt="Product 1">
                     </div>
                     <div class="item-details">
                        <h2><?php echo $arrangerDetails['full_name']; ?></h2>
                        <p class="service-location"><?php echo $arrangerDetails['address']; ?></p>
                        <p class="shop-num"><?php echo $arrangerDetails['phone']; ?></p>
                     
                     </div>
                     <div class="border"></div>
                  </div>
                  <div class="border mt-2"></div>
                  <?php
                  // Retrieve service_id from the query parameters
                  $serviceID = $_GET['service_id'];

                  // Retrieve packages data from the query parameters
                  $packagesJSON = $_GET['packages'];
                  $selectedPackages = json_decode(urldecode($packagesJSON), true);

                  // Function to get package details by package_id
                  function get_package_details($packageID, $pdo) {
                     $sql = "SELECT * FROM service_package WHERE package_id = :package_id";
                     $stmt = $pdo->prepare($sql);
                     $stmt->bindParam(':package_id', $packageID);
                     $stmt->execute();
                     return $stmt->fetch(PDO::FETCH_ASSOC);
                  }

                  // Connect to the database
                  $pdo = dbconnect();

                  ?>

                  <!-- HTML code for displaying selected packages -->
                  <label class="arranger "style="margin-bottom:40px">Packages</label>
                     <?php foreach ($selectedPackages as $selectedPackage): ?>
                        <?php
                        // Retrieve package details by package_id
                        $packageDetails = get_package_details($selectedPackage['id'], $pdo);
                        ?>
                       
   
                     <input type="hidden" name="package_ids[]" value="<?php echo $selectedPackage['id']; ?>">

                     <div class="cart-item" style="margin-bottom:30px;">
                     
                     <div class="custom" style="margin-top:-25px;">
                        <img src="<?php echo $packageDetails['package_image']; ?>" alt="Product 1" style=" width: 80px;height: 85px;">
                     </div>
                     <div class="item-details" style="margin-left:-60px;margin-top:-50px">
                        <h2><?php echo $packageDetails['package_name']; ?></h2>
                        <p class="service-location"><?php echo '₱ ' . $packageDetails['package_price']; ?></p>
                       
                     </div>
                     </div>
                     <?php endforeach; ?>
            
                  <div class="border"></div>
                  <?php
                  // Close the database connection
                  $pdo = null;
                  ?>

                  
               </form>
            </div>
            <div class="column2">
               <div class="summary-container">
                  
                     

                 
                  <div class="button-container">
                     <div class="button-container">
                     
                     <?php
                           // ... (Previous code)

                           // Function to get package details by package_id
                           function get_package($packageID, $pdo) {
                              $sql = "SELECT * FROM service_package WHERE package_id = :package_id";
                              $stmt = $pdo->prepare($sql);
                              $stmt->bindParam(':package_id', $packageID);
                              $stmt->execute();
                              return $stmt->fetch(PDO::FETCH_ASSOC);
                           }

                           // Connect to the database
                           $pdo = dbconnect();

                           // Calculate the total amount
                           $totalAmount = 0;
                           foreach ($selectedPackages as $selectedPackage) {
                              $packageDetails = get_package($selectedPackage['id'], $pdo);
                              $totalAmount += $packageDetails['package_price'];
                           }
                           ?>

                           
                              <div class="total-info">
                              <p class="total-item">Total</p>
                              <p class="total-price" id="display-total-price-near-button">₱ <?php echo number_format($totalAmount, 2); ?></p>
                              <input type="hidden" id="total_price_input" name="total_price" value="<?php echo $totalAmount; ?>">

                           </div>

                           <?php
                           // ... (Remaining code)
                           // Close the database connection
                           $pdo = null;
                           ?>
                   
                        <button class="checkout" id="placeOrderBtn">Place Request</button>
                        <!-- Confirmation Modal -->
                        <div id="confirmationModal" class="modal">
                           <div class="modal-content">
                              <p class="confirm-order">Do you want to confirm your request?</p>
                              <p class="confirm-note">Once confirmed, it cannot be canceled.</p>
                              <div class="confirm-btn">
                                <button class="cancel" id="cancelOrderBtn">Cancel</button>
                                <button class="confirm" id="confirmOrderBtn">Confirm</button>
                              </div>
                           </div>
                        </div>
                        </form>
                        <!-- Thank You Modal (Initially hidden) -->
                        <div id="thankYouModal" class="modal">
                           <div class="modal-content">
                              <span class="close" id="closeThankYouModal">&times;</span>
                              <i class="bi bi-check-circle"></i>
                              <h2 class="confirmed">Service Request Confirmed!</h2>
                              <p class="successful" style="font-size:13px">Your request has been placed successfully. Check the status of your service request here. <a href="#" style=" color:#ff7e95;">Service Status</a></p>
                              <!-- Continue Shopping Button -->
                              <a href="customer_home.php"><button class="c-shopping" id="continueShoppingBtn">Continue Shopping</button></a>
                           </div>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
         </div>
      </main>
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <script>
     document.addEventListener("DOMContentLoaded", function () {
    const confirmationModal = document.getElementById("confirmationModal");
    const thankYouModal = document.getElementById("thankYouModal");
    const placeOrderBtn = document.getElementById("placeOrderBtn");
    const confirmOrderBtn = document.getElementById("confirmOrderBtn");
    const cancelOrderBtn = document.getElementById("cancelOrderBtn");
    const continueShoppingBtn = document.getElementById("continueShoppingBtn");
    const dateInput = document.getElementById('date-input');
    const timeInput = document.getElementById('time-input');

    // Show the confirmation modal when the "Place Request" button is clicked
    placeOrderBtn.addEventListener("click", (event) => {
        event.preventDefault(); // Prevent form submission
        if (!dateInput.value || !timeInput.value) {
            alert("Please fill in all required fields.");
            return;
        }
        confirmationModal.style.display = "block";
    });

    confirmOrderBtn.addEventListener("click", function (event) {
    event.preventDefault();

    // Show the thank you modal
    confirmationModal.style.display = "none";
    thankYouModal.style.display = "block";

    // Prepare data for form submission
    var formData = new FormData(document.getElementById('service-request-form'));
    formData.append('total_price', document.getElementById('total_price_input').value); // Add total_price to form data
    formData.append('package_id', document.getElementById('package_id_input').value); // Add package_id to form data

    // Perform AJAX form submission
    fetch('service_request.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            console.log(data); // You can process the response here

            // Redirect after 10 seconds
            setTimeout(() => {
                thankYouModal.style.display = "none";
                window.location.href = 'customer_home.php';
            }, 10000);
        })
        .catch(error => console.error('Error:', error));
});
    // Close the confirmation modal when the "Cancel" button is clicked
    cancelOrderBtn.addEventListener("click", () => {
        confirmationModal.style.display = "none";
    });

    // Close the thank you modal when the "Continue Shopping" button is clicked
    continueShoppingBtn.addEventListener("click", () => {
        thankYouModal.style.display = "none";
    });

    // ... (Remaining code)
});

</script>


<script>
// Add this script to set the package_id_input value before submitting the form
document.getElementById('confirmOrderBtn').addEventListener('click', function() {
    var selectedPackages = <?php echo json_encode($selectedPackages); ?>;
    document.getElementById('package_id_input').value = selectedPackages[0]['id']; // Assuming you're only processing one package
});
</script>


       <script>
         function goBack() {
             window.history.back();
         }
       </script>  
   </body>
</html>
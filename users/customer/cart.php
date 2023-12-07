<?php
session_start();
include '../php/dbhelper.php';

$products = array(); // Initialize an array to store the product details
$_SESSION['selected_products'] = $products;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Retrieve the product IDs added to the cart by the user from the sales_details table
    $productIDs = get_product_ids_in_cart($user_id);

    // Fetch product details for each product in the cart
foreach ($productIDs as $ids) {
    $productID = $ids['product_id'];
    $salesdetailsId = $ids['salesdetails_id'];

    $product = get_product_details($productID, $salesdetailsId);
    if ($product) {
        // Add the fetched product details to the products array
        $products[] = $product;
    }
}


    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        
        // Retrieve the user's address and zipcode from the database
        $user = get_record('users', 'user_id', $user_id);
  
    
        if ($user) {
            $address = $user['address']; // Replace 'address' with the actual column name
            $zipcode = $user['zipcode']; // Replace 'zipcode' with the actual column name
            $phone = $user['phone']; // Replace 'zipcode' with the actual column name
        }
    }
    
}

// Function to get product IDs added to the cart by the user
function get_product_ids_in_cart($user_id) {
    $conn = dbconnect(); // Connect to the database

    // Define the table and where clause
    $table = 'salesdetails';
    $where = 'customer_id';

    $productIDs = array(); // Initialize an array to hold the product details

    // Prepare the SQL query to fetch product_id and salesdetails_id
    $sql = "SELECT product_id, salesdetails_id FROM $table WHERE $where = ?";

    try {
        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Populate the productIDs array with the fetched data
        foreach ($result as $row) {
            $productIDs[] = array(
                'product_id' => $row['product_id'],
                'salesdetails_id' => $row['salesdetails_id']
            );
        }
    } catch (PDOException $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $conn = null;

    return $productIDs; // Return the array of product IDs and salesdetails IDs
}


// Function to get product details by joining the sales_details and products tables
function get_product_details($productID, $salesdetailsId) {
    $conn = dbconnect();
    // Update the SQL query to include salesdetails_id in the SELECT statement
    $sql = "SELECT p.*, sd.quantity, sd.salesdetails_id, sd.flower_type, sd.ribbon_color, sd.message
            FROM products p 
            JOIN salesdetails sd ON p.product_id = sd.product_id
            WHERE p.product_id = ? AND sd.salesdetails_id = ?";

    try {
        $stmt = $conn->prepare($sql);
        // Execute the statement with both productID and salesdetailsId
        $stmt->execute([$productID, $salesdetailsId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        return false;
    }

    $conn = null;
    return $product;
}



// Function to check if a product with a given salesdetails_id exists in the sales table
function is_salesdetails_id_in_sales_table($salesdetailsId) {
    $conn = dbconnect(); // Connect to the database

    // Define the table and where clause
    $table = 'sales';
    $where = 'salesdetails_id';

    // Prepare the SQL query to check if salesdetails_id is in the sales table
    $sql = "SELECT COUNT(*) as count FROM $table WHERE $where = ?";

    try {
        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);
        $stmt->execute([$salesdetailsId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if salesdetails_id is in the sales table
        return ($result['count'] > 0);
    } catch (PDOException $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
        return false;
    } finally {
        // Close the database connection
        $conn = null;
    }
}

?>

<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cart</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        
        <style>
             
            @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
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
            .nav-hr{
                width:60%;
                margin: auto;
                margin-top:-6px;
            }
            #search-results{
                display:none;
            }
            .back{
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
            .number, .address, .zipcode{
                font-size: 13px;
                margin-top: 25px;
            }
            .cart-item {
                display: flex;
                align-items: center;
                padding:20px;
                margin-top: 30px;
            }
        
            .item-details .p_name{
                font-size: 16px;
                color:#555;
                margin-top: -35px;
                width:400px;
                font-weight: 400;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .all-items .item-checkbox {
                margin-left: 10px;
            }
            .sub-price{
                color:#555;
                margin-left:200px;
            }
            .cart-item img {
                width: 100px;
                height: 250px;
                margin-right: 20px;
            }
            .flower-type{
                display: flex;
                gap:10px;
                margin-top:10px;
            }
            .flower{
                font-size: 13px;
                color:#777;
            }
            .type{
                font-size: 13px;
                color:#666;
            }
            .ribbon-color{
                display: flex;
                gap:10px;
                margin-top:-5px;
            }
            .ribbon{
                font-size: 13px;
                color:#777;
            }
            .color{
                font-size: 13px;
                color:#666;
            }

            .modal {
                display: none;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
            }
            .modal-content {
                background-color: rgba(40, 42, 42, 0.7); /* Adjust the opacity value (0.5 in this case) */
                margin: 24% auto;
                padding-top: 10px;
                border: none;
                border-radius: 10px;
                max-width: 300px;
                text-align: center;
                color: white;
            }
            .bi-info-circle {
                font-size: 50px;
                color: white;
                margin: auto;
                margin-top: 5%;
            }
            .location{
                border-radius: 10px;
                border:1px solid #65A5A5;
                padding:10px;
                width:120%;
                display: flex;
                margin-top: 20px;
                text-align: left;
            }
            .none{
                font-size: 15px;
                text-align: center;
                margin-top: 30px;
                padding-bottom: 30px;
                color:#666;
            }
            .location i{
                margin-right: 10px;
                position: relative;
                font-size:30px;
                color:#666;
            }
            .location-info {
                flex-direction: column;
                font-size:13px;
                line-height:20%;
                margin-top: 2.5%;
                position: relative;
                margin-left:10px;
             

            }
            .loc{
                font-size: 15px;
            }
            .street{
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
            .all-items{
                display: flex;
                background-color: #f0f0f0;
                padding:10px;
                top:0px;
            }
            input[type="checkbox"] {
                transform: scale(1.5);
                background-color: #65A5A5;
            }
            .all-checkbox{
                margin-left:13px;
                background-color: #65A5A5;
            }
            .items-label{
                font-size: 15px;
                margin-top:2%;
                margin-left:25px;
                color:#555;
            }
            .edit{
                background-color: transparent;
                border:none;
                color:#666;
                padding: 10px 5px;
                border-radius: 10px;
                font-size: 15px;
                margin-left:600px;
                position:absolute;

            
            
            }
            .edit:focus{
                outline: none;
            }
            .cart-item {
                display: flex;
                align-items: center;
                padding:20px;
            }
            .shop-info {
                flex-direction: column;
                font-size:13px;
                line-height:5%;
                margin-top: 2%;
                position: relative;
            }
            .shop-info img {
                width: 35px;
                height: 35px;
                margin-top:2px;
                margin-left: 13px;
                border-radius:50px;
            }
            .shop-info p{
                margin-top:-35px;
            }
            .shop-info a p{
                font-size: 14px;
                margin-left: 60px;
                margin-top:-25px;
                color:#555;
            }
            .shop-name a{
                display: flex;
                align-items: center;
                flex-grow: 1;
            }
            .shop-name a:hover{
                text-decoration: none;
            }
            .fa.fa-angle-right {
                margin-left: 10px;
                margin-top:-33px;
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
                max-height: 100px;
                margin-right: 20px;
            }

            .item-details p {
                margin: 5px 0;
            }
            
            .flower-type{
                display: flex;
                gap:10px;
                margin-top:-5px;
            }
            .flower{
                font-size: 13px;
                color:#777;
            }
            .type{
                font-size: 13px;
                color:#666;
            }
            .ribbon-color{
                display: flex;
                gap:10px;
                margin-top:-5px;
            }
            .ribbon{
                font-size: 13px;
                color:#777;
            }
            .color{
                font-size: 13px;
                color:#666;
            }
            .price{
                color:#666;
                font-weight: 500;
                font-size: 15px;
            }
            .quantity-control {
                display: flex;
                position: absolute;
                margin-top:-31px;
                left: 51.7%;
                transform: translateX(-50%);
            }
            input[type="text"] {
                width: 50px;
                text-align: center;
                font-size: 14px;
                padding:1px;
                border: 1px solid #d2cfcf;
                color:#666;
            }
            .quantity-button {
                background-color: transparent;
                color: #666;
                border: 1px solid #d2cfcf;
                cursor: pointer;
                padding: 1px 10px;
                font-size: 15px;
                border-radius: 2px;
            }
            .column2 {
                flex-basis: 37%;
                margin-top:21px;
            }
            .border{
                height:8px;
                background-color: #f0f0f0;
            }
            .summary-container {
                width: 100%;
                margin-top: 10px ;
                background-color: #fff;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            }
            .order-summary{
                display: flex;
                background-color: #f0f0f0;
                padding:10px;
                top:0px;
            }
            .order-label{
                margin-top:5px;
                margin-left:10px;
            }
            .summary-items{
                padding:20px;
            }
            .sub-label{
                color:#555;
                white-space: nowrap;
            }
            .sub-price{
                color:#555;
                margin-right:20px;
                white-space: nowrap;    
            }
            .sub-total{
                display: flex;
                white-space: nowrap;
                justify-content: space-between;
            }
            .button-container {
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .checkout a{
                color: #fff;
                text-decoration: none;
            }
            .checkout{
                background-color: #65A5A5;
                color:white;
                border:none;
                outline:none;
                padding: 10px;
                padding-left: 50px;
                padding-right: 50px;
                width:91%;
                border-radius: 10px;
                margin-bottom: 20px;
                text-align: center;
            }
            .checkout:focus{
                outline:none;
                border:none;
            }
            /*Responsiveness*/
            @media (max-width: 768px) {
                .navbar{
                    position: fixed;
                    background-color: white;
                    width:100%;
                    z-index: 100;
                    top:0px;
                }
                .navbar img {
                    display: none;
                }
                .form-input[type="text"] {
                    display: none;
                }
                .nav-hr{
                    width:100%;
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
                .back{
                    display: block;
                    font-size: 20px;
                }
                .form-inline .fa-search {
                    display: none;
                }
                .form-inline .back{
                    text-decoration: none;
                    color:#666;
                }
                .form-inline .fa-angle-left:focus {
                    text-decoration: none;
                    outline: none;
                }
                .container {
                    margin-top: 50px;
                    display: flex;
                    flex-direction: column;
                    gap: 20px;
                }
                .column1 {
                    display: flex;
                    flex-direction: column;
                    margin:0;
                    margin-left:0;
                    margin-bottom: 40px;
                }
                .none{
                    font-size: 13px;
                    text-align: center;
                    margin-top: 30px;
                    padding-bottom: 30px;
                    color:#666;
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
                   
                    
                    margin-top:5px;
                    position: relative;
                    flex-direction: row; /* Align items horizontally for larger screens */

                 
                }
                .loc{
                    font-size: 12px;
                    
                }
                .number{
                    font-size: 12px;
                    margin-top: 25px;
                   
                    
                }
                .zipcode{
                    font-size: 12px;
                    margin-top: 25px;
                    
                    
                }
                .address{
                    font-size: 12px;
                    margin-top: 25px;
                    
                    
                }
                .street{
                    font-size: 12px;
                    margin-top: 20px;
                    margin-left:-10px;
                }
                .cart-container {
                    width: 100%;
                    margin: 20px auto;
                    background-color: #fff;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
                }
                .all-items{
                    display: flex;
                    background-color: #f0f0f0;
                    padding:5px;
                    top:0px;
                }
                input[type="checkbox"] {
                    transform: scale(1.1);
                    background-color: #65A5A5;
                }
                .all-checkbox{
                    margin-left:11px;
                    background-color: #65A5A5;
                }
                .items-label{
                    font-size: 13px;
                    margin-top:4%;
                    color:#555;
                }
                .edit{
                    background-color: transparent;
                    border:none;
                    color:#666;
                    padding: 10px 5px;
                    border-radius: 10px;
                    font-size: 15px;
                    margin-left:300px;
                
                
                }
                .cart-item {
                    margin-bottom: 50px;
                    padding:20px;
                }
                .shop-info {
                    flex-direction: column;
                    text-align:left ;
                    margin-left:5px;
                    line-height:5%;
                    margin-top: 2%;
                    position: relative;
                }
                .shop-info img {
                    width: 25px;
                    height: 25px;
                    margin-top:2px;
                    margin-left: 10px;
                    border-radius:50px;
                }
                .shop-info a p{
                    font-size: 12px;
                    margin-left: 45px;
                    margin-top:-14px;
                    color:#555;
                }
                .shop-name a{
                    display: flex;
                    align-items: center;
                    flex-grow: 1;
                }

                .fa.fa-angle-right {
                    margin-left: 10px;
                    margin-top:-29px;
                    color: #555;
                    font-size: 16px;
                }

                .cart-hr{
                    margin-top:5px;
                }
                .custom-checkbox {
                    width:50px;
                    display: flex;
                    
                }
                .item-checkbox {
                    margin-right: -1px;
                    margin-left: -5px;
                    margin-top: 0px;
                    width: 13px;
                    height: 13px;
                }
                .cart-item img {
                    max-width: 80px;
                    height:90px;
                    max-height: 100px;
                    margin-right: 20px;
                    margin-top:-10px;
                    margin-left:11px;
                }
                .item-details{
                    margin-top: -25px;
                    margin-left:5px;
                    text-align: left;
                }
                .item-details .p_name{
                    font-size: 12px;
                    color:#555;
                    margin-left:60px;
                    margin-top:-40px;
                    width:170px;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    position: sticky;
                    
          
                }
                .item-details p {
                    margin: 5px 0;
                }
                .flower-type{
                    display: flex;
                    justify-content:flex-start;
                    width:190px;
                    margin-top:-1px;
                    margin-left:60px;
                    width:170px;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    position: sticky;
                }
                .flower{
                    color:#777;
                    font-size: 11px;
                }
                .type{
                    color:#777;
                    font-size: 11px;
                    margin-left:-105px;
                }
                .ribbon-color{
                    display: flex;
                    gap:10px;
                    margin-top:-3px;
                    margin-left:60px;
                   
                }
                .ribbon{
                    font-size: 11px;
                    color:#777;
                    width:160px;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    position: sticky;
                }
                .color{
                    font-size: 11px;
                    color:#666;
                }
                .price{
                    color:#666;
                    font-weight: 400;
                    font-size: 12px;
                    display: flex;
                    position: absolute;
                    left: 167px;
                    transform: translateX(-50%);
                }
                .quantity-control {
                    display: flex;
                    position: absolute;
                    margin-top:5px;
                    left: 79%;
                    transform: translateX(-50%);
                }
                input[type="text"] {
                    width: 40px;
                    height:20px;
                    text-align: center;
                    font-size: 11px;
                    padding:1px;
                    border: 1px solid #d2cfcf;
                    color:#666;
                }
                .quantity-button {
                    background-color: transparent;
                    color: #666;
                    height:20px;
                    border: 1px solid #d2cfcf;
                    cursor: pointer;
                    padding: 1px 7px;
                    font-size: 12px;
                    border-radius: 2px;
                }
                .quantity-button:focus{
                    outline:none;
                }
                .column2 {
                    flex-basis: 37%;
                    margin-top:21px;
                }
                .border{
                    height:8px;
                    margin-top:-20px;
                    background-color: #f0f0f0;
                }
                .summary-container {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    height:9%;
                    background-color: #fff;
                    box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
                    display: flex;
                    justify-content: space-between;
                    padding: 10px 10px;
                    z-index: 100;
                    border-radius: 0px;
                }
                .order-summary{
                    display: none;
                }
                .order-label{
                    display:none;
                }
                .summary-items{
                    padding:20px;
                }
                .sub-label{
                    display:none;
                }
                .sub-total{
                    display: flex;
                    white-space: nowrap;
                    justify-content: space-between;
                }
                .button-container {
                    padding-right:20px;
                    margin-top: 20px;
                    margin-left: -20px;
                    max-height: 120px;
                }
                .sub-price {
                    margin-left:0px;
                    margin-top:-10px;
                    font-size:13px;
                    white-space: nowrap;
                }
                .checkout{
                    background-color: #65A5A5;
                    color:white;
                    border:none;
                    outline:none;
                    width:100%;
                    border-radius: 10px;
                    font-size: 13px;
                    margin-left:30px;
                    margin-right:-15px;
                    margin-top:1px;
                }
                .checkout a{
                    color: #fff;
                    text-decoration: none;
                }
                .checkout:focus{
                    outline:none;
                }
                .modal {
                display: none;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
            }
            .confirm-order{
                margin-top: 5px;
            }
                .modal-content {
                    background-color: rgba(40, 42, 42, 0.7); /* Adjust the opacity value (0.5 in this case) */
                    margin: 85% auto;
                    padding-top: 10px;
                    border: none;
                    border-radius: 10px;
                    max-width: 180px;
                    text-align: center;
                    color: white;
                    font-size: 12px;
                }
                /* Media query to adjust alignment for smaller screens */
                @media (max-width: 768px) {
                    .product {
                        width: calc(50% - 15px);
                    }
                    .product-list{
                        gap: 10px;
                    }
                    .column1 {
                        text-align: center;
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
                                    <div id="search-results">Shopping Cart</div>
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <main class="main">
            <div class="container">
                <div class="column1">
                    <div class="location">
                        <i class="bi bi-geo-alt"></i>
                        <div class="location-info">
                            <?php if (isset($address) && isset($zipcode) && isset($phone)) { ?>
                                <p class="loc">Delivering to: </p>
                                <p class="address"><?php echo $address . ', ' . $zipcode; ?></p>
                                <p class="number"> <?php echo $phone; ?></p>
                            <?php } else { ?>
                                <p class="loc">Address not available</p>
                            <?php } ?>
                            
                        </div>
                    </div>
                    <div class="cart-container">
                        <div class="all-items">
                            <h6 class="items-label">Products</h6>
                        </div>
                      
                            <div class="cart-items">
     
                           
                                        <?php
                                        $productsByShop = array();

                                        $removedSalesDetailsIds = array(); 
                                        // Loop through each product and organize them by shop_id
                                        foreach ($products as $product) {
                                            $shopId = $product['shop_owner'];
                                            if (!isset($productsByShop[$shopId])) {
                                                $productsByShop[$shopId] = array();
                                            }
                                        
                                            // Check if the salesdetails_id is already in the sales table
                                            if (is_salesdetails_id_in_sales_table($product['salesdetails_id'])) {
                                                $removedSalesDetailsIds[] = $product['salesdetails_id'];
                                                continue; // Skip this product if its salesdetails_id is in the sales table
                                            }
                                        
                                            $productsByShop[$shopId][] = $product;
                                        }
                                        $productsByShop = array_filter($productsByShop);
                                        if (empty($productsByShop)) {
                                            echo '<p class="none">No products added to cart.</p>';
                                        } else {
                                                // Loop through each shop and display its products
                                                foreach ($productsByShop as $shopId => $shopProducts) {
                                                    // Retrieve shop details from the database based on shop_id
                                                    $shop = get_record('shops', 'shop_id', $shopId);
                                                
                                                    // Display shop details
                                                    if ($shop) {
                                                        echo '<div class="shop-info">';
                                                        echo '<img src="' . $shop['shop_img'] . '" alt="Shop Image">';
                                                        echo '<div class="shop-name">';
                                                        echo '<a href="../vendor/vendor_shop.html">';
                                                        echo '<p>' . $shop['shop_name'] . '</p>';
                                                        echo '</a>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                    } else {
                                                        echo "Shop details not found.";
                                                    }
                                                
                                                    // Display products for the current shop
                                                    echo '<hr class="cart-hr">';
                                                    echo '<div class="cart-items">';
                                                    $shopHasProducts = false; // Flag to track if the shop has any products to display

                                                    foreach ($shopProducts as $product) {
                                                        echo '<div class="cart-item">';
                                                        echo '<div class="custom-checkbox" style="margin-top: -30px">';
                                                        echo '<input type="checkbox" class="item-checkbox" data-shopId="' . $shopId . '" data-productId="' . $product['product_id'] . '" data-productName="' . $product['product_name'] . '" data-productPrice="' . $product['product_price'] . '" data-quantity="' . $product['quantity'] . '" data-salesdetails-id="' . $product['salesdetails_id'] . '">';
                                                        echo '<img src="' . $product['product_img'] . '" alt="' . $product['product_name'] . '">';
                                                        echo '</div>';
                                                
                                                        // Rest of your product display code...
                                                        echo '<div class="item-details">';
                                                        echo '<p class="p_name">' . $product['product_name'] . '</p>';                
                                                        //Retrieve flower type and ribbon color from salesdetails table
                                                        $salesDetails = $product;

                                                        if ($salesDetails) {
                                                            // Initialize an array to store customization details
                                                            $customization = array();
                                                        
                                                            // Check if flower type is available
                                                            if (!empty($salesDetails['flower_type'])) {
                                                                $customization[] = $salesDetails['flower_type'];
                                                            }
                                                        
                                                            // Check if ribbon color is available
                                                            if (!empty($salesDetails['ribbon_color'])) {
                                                                $customization[] = $salesDetails['ribbon_color'];
                                                            }
                                                        
                                                            // Display the customization details
                                                            if (!empty($customization)) {
                                                                echo '<div class="ribbon-color">';
                                                                echo '<p class="ribbon">'. implode(', ', $customization) .'</p>';
                                                                echo '</div>';
                                                            }
                                                        
                                                            if ($salesDetails) {
                                                                // Initialize a variable to store the message
                                                                $message = !empty($salesDetails['message']) ? $salesDetails['message'] : 'None';
                                                            
                                                                // Display the message details
                                                                echo '<div class="ribbon-color">';
                                                                echo '<p class="ribbon">Message: ' . $message .'</p>';
                                                                echo '</div>';
                                                            }
                                                            
                                                        }

                                                                    
                                                        echo '<p class="price">₱ ' . $product['product_price'] . '</p>';
                                                        echo '<div class="quantity-control">';
                                                        echo '<button class="quantity-button" data-product-id="' . $product['product_id'] . '" data-salesdetails-id="' . $product['salesdetails_id'] . '" data-action="decrease">-</button>';
                                                        echo '<input type="text" id="quantity' . $product['product_id'] . '" value="' . $product['quantity'] . '">';
                                                        echo '<button class="quantity-button" data-product-id="' . $product['product_id'] . '" data-salesdetails-id="' . $product['salesdetails_id'] . '" data-action="increase">+</button>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>'; // End of cart-item
                                                    }
                                                
                                                    echo '</div>'; // End of cart-items
                                                    echo '<div class="border"></div>';
                                                }
                                            }
                                            $products = array_filter($products, function ($product) use ($removedSalesDetailsIds) {
                                                return !in_array($product['salesdetails_id'], $removedSalesDetailsIds);
                                            });
                                            
                                            $_SESSION['selected_products'] = $products;
                                        ?>
                                 

                            
                                <!-- Assuming this is your existing HTML structure -->
                                
                                <!-- End of loop -->
                               
                            </div>
                       
                        
                    </div>
                </div>
                <div class="column2">
                    <div class="summary-container">
                        <div class="order-summary">
                            <h6 class="order-label">Order Summary</h6>
                        </div>
                        <div class="summary-items">
                            <div class="sub-total">
                                <p class="sub-label">Sub-total</p>
                                <p class="sub-price" id="subTotalPrice">₱ 0</p>
                            </div>
                        </div>
                        <div class="button-container">
                       
                            <button class="checkout" onclick="goToPlaceOrder()">Checkout</button>
                            <div id="confirmationModal" class="modal">
                                 <div class="modal-content">                                     
                                    <p class="confirm-order">Please select item(s).</p>
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
            // JavaScript to handle quantity changes for each product
            <?php foreach ($products as $product) { ?>
                const quantityInput<?= $product['product_id'] ?> = document.getElementById('quantity<?= $product['product_id'] ?>');
                const decreaseButton<?= $product['product_id'] ?> = document.querySelector('button[data-product-id="<?= $product['product_id'] ?>"][data-action="decrease"]');
                const increaseButton<?= $product['product_id'] ?> = document.querySelector('button[data-product-id="<?= $product['product_id'] ?>"][data-action="increase"]');

                decreaseButton<?= $product['product_id'] ?>.addEventListener('click', () => {
                    decreaseQuantity<?= $product['product_id'] ?>();
                });

                increaseButton<?= $product['product_id'] ?>.addEventListener('click', () => {
                    increaseQuantity<?= $product['product_id'] ?>();
                });

                function decreaseQuantity<?= $product['product_id'] ?>() {
                let currentQuantity = parseInt(quantityInput<?= $product['product_id'] ?>.value);
                if (currentQuantity > 1) {
                    currentQuantity--;
                    quantityInput<?= $product['product_id'] ?>.value = currentQuantity;
                    updateQuantityOnServer('decrease', <?= $product['product_id'] ?>, currentQuantity);
                    updateSubtotal();
                }
            }

            function increaseQuantity<?= $product['product_id'] ?>() {
                let currentQuantity = parseInt(quantityInput<?= $product['product_id'] ?>.value);
                currentQuantity++;
                quantityInput<?= $product['product_id'] ?>.value = currentQuantity;
                updateQuantityOnServer('increase', <?= $product['product_id'] ?>, currentQuantity);
                updateSubtotal();
            }

           
        <?php } ?>
    </script>

        <script>
            const selectedProducts = [];
                        // Function to save the checkbox state in local storage
                function saveCheckboxState(id, isChecked) {
                    localStorage.setItem(id, isChecked ? 'checked' : 'unchecked');
                }

                // Function to load the checkbox state from local storage
                function loadCheckboxState(id) {
                    const state = localStorage.getItem(id);
                    return state === 'checked';
                }

             
                            // Function to save the checkbox state in local storage
                function saveCheckboxState(id, isChecked) {
                    localStorage.setItem(id, isChecked ? 'checked' : 'unchecked');
                }

                // Function to load the checkbox state from local storage
                function loadCheckboxState(id) {
                    const state = localStorage.getItem(id);
                    return state === 'checked';
                }

                const shopCheckboxes = {}; // Object to store shop checkboxes

                const itemCheckboxes = document.querySelectorAll('.item-checkbox');

                // Handle checkbox changes
                itemCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        const shopId = this.dataset.shopid; // Ensure camelCase here

                        // Uncheck checkboxes in other shops
                        for (const id in shopCheckboxes) {
                            if (id !== shopId) {
                                shopCheckboxes[id].forEach(otherCheckbox => {
                                    otherCheckbox.checked = false;
                                    saveCheckboxState(otherCheckbox.id, false);
                                });
                            }
                        }

                        updateSubtotal();
                    });

                    // Store checkboxes in the shopCheckboxes object
                    const shopId = checkbox.dataset.shopid; // Ensure camelCase here
                    if (!shopCheckboxes[shopId]) {
                        shopCheckboxes[shopId] = [];
                    }
                    shopCheckboxes[shopId].push(checkbox);
                });

                // JavaScript to reset checkboxes on page load and load their state from local storage
                window.addEventListener('load', function () {
                    itemCheckboxes.forEach(checkbox => {
                        const isChecked = loadCheckboxState(checkbox.id);
                        checkbox.checked = isChecked; // Set checkboxes based on local storage

                        const shopId = checkbox.dataset.shopid;
                        if (!shopCheckboxes[shopId]) {
                            shopCheckboxes[shopId] = [];
                        }
                        shopCheckboxes[shopId].push(checkbox);
                    });

                    updateSubtotal();
                });

                const quantityButtons = document.querySelectorAll('.quantity-button');

                quantityButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const quantityInput = this.parentNode.querySelector('input[type="text"]');
                        let currentQuantity = parseInt(quantityInput.value);
                        const productId = this.dataset.productId;
                        const salesdetailsId = this.dataset.salesdetailsId; // Add this line
                        const action = this.dataset.action;

                        if (action === 'increase') {
                            currentQuantity++;
                        } else if (action === 'decrease') {
                            if (currentQuantity > 1) {
                                currentQuantity--;
                            }
                        }

                        quantityInput.value = currentQuantity;
                        updateQuantityOnServer(action, productId, salesdetailsId, currentQuantity); // Update this line
                        // Call your update function here if needed
                    });
                });

                // Function to send an AJAX request to update the quantity on the server
                function updateQuantityOnServer(action, productId, salesdetailsId, newQuantity) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '../php/update_quantity.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            // Quantity updated successfully on the server
                            location.reload();                        }
                    };

                    const data = `action=${action}&product_id=${productId}&salesdetails_id=${salesdetailsId}&quantity=${newQuantity}`;
                    xhr.send(data);
                }




            // JavaScript to calculate and update the sub-total price
            function updateSubtotal() {
                let subtotal = 0;

                itemCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const itemContainer = checkbox.closest('.cart-item');
                        const priceElement = itemContainer.querySelector('.price');
                        const price = parseFloat(priceElement.textContent.replace('₱ ', ''));
                        const quantityElement = itemContainer.querySelector('input[type="text"]');
                        const quantity = parseInt(quantityElement.value);

                        subtotal += price * quantity;
                    }
                });

                const subTotalPriceElement = document.getElementById('subTotalPrice');
                subTotalPriceElement.textContent = '₱ ' + subtotal.toFixed(2); // Format the subtotal price

                
            }

                function updateCheckoutButton() {
                    // Check if at least one product is selected
                    const isAtLeastOneProductSelected = Array.from(itemCheckboxes).some(checkbox => checkbox.checked);

                    // Disable or enable the checkout button based on the selection
                    checkoutButton.disabled = !isAtLeastOneProductSelected;

                    // Change background color of the checkout button
                    checkoutButton.style.backgroundColor = isAtLeastOneProductSelected ? '#28a745' : '#6c757d';
                }

                // Handle checkbox changes
                itemCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        updateSubtotal();
                        updateCheckoutButton();
                    });
                });

                // Function to save and load checkbox state
                function saveCheckboxState(id, isChecked) {
                    localStorage.setItem(id, isChecked ? 'checked' : 'unchecked');
                }

                function loadCheckboxState(id) {
                    const state = localStorage.getItem(id);
                    return state === 'checked';
                }
                   
            
        </script>
        <script>
           
           function goToPlaceOrder() {
            const isAtLeastOneProductSelected = Array.from(itemCheckboxes).some(checkbox => checkbox.checked);
            const confirmationModal = document.getElementById('confirmationModal');

            if (isAtLeastOneProductSelected) {
                const selectedProducts = getSelectedProducts();
                const selectedProductsParam = encodeURIComponent(JSON.stringify(selectedProducts));

                // Perform any additional actions before showing the modal if needed

                window.location.href = 'place_order.php?selected_products=' + selectedProductsParam;
            } else {
                // Display the modal
                confirmationModal.style.display = 'block';

                // Automatically close the modal after 2 seconds
                setTimeout(function () {
                    confirmationModal.style.display = 'none';
                }, 2000);
            }
        }



            function getSelectedProducts() {
                const itemCheckboxes = document.querySelectorAll('.item-checkbox');
                const selectedProducts = [];

                itemCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const shopId = checkbox.getAttribute('data-shopId');
                        const productId = checkbox.getAttribute('data-productId');
                        const productName = checkbox.getAttribute('data-productName');
                        const productPrice = parseFloat(checkbox.getAttribute('data-productPrice'));
                        const quantity = parseInt(checkbox.getAttribute('data-quantity'));
                        const salesdetailsId = checkbox.getAttribute('data-salesdetails-id');

                        const productDetails = {
                            shopId: shopId,
                            productId: productId,
                            productName: productName,
                            productPrice: productPrice,
                            quantity: quantity,
                            salesdetailsId: salesdetailsId
                        };
                        selectedProducts.push(productDetails);
                    }
                });

                return selectedProducts;
            }

        </script>

            
        


        <script>
            function goBack() {
                window.history.back();
            }
          </script>
            
    </body>
</html>
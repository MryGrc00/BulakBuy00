<?php
session_start();
include '../php/dbhelper.php';
include '../php/checksession.php';
$products = null;
$user = null;



if (isset($_SESSION["user_id"])) {
    $seller_id = $_SESSION["user_id"];
    $products = get_latest_products_by_id('products','shops','subscription');
    $services = get_latest_services('services','users','shops','subscription');

}

if (isset($_GET['product_id']) && isset($_SESSION['user_id'])) {
    $productID = $_GET['product_id'];
    $userID = $_SESSION['user_id'];

    $product = get_record('products', 'product_id', $productID);
    
    // Assuming 'shop_owner' is the field in 'products' that references 'shop_id' in 'shops'
    $shop = get_record('shops', 'shop_id', $product['shop_owner']);
    
    // Retrieve user details (owner of the shop) from the database
    $user = get_record('users', 'user_id', $shop['owner_id']);

    $isArranger = isset($user['role']) && $user['role'] === 'arranger';


    } 



// Get the image file names (or paths) stored in the database
$imageFileNames = explode(',', $product['product_img']);

// Base path to the directory where images are stored
$imageBasePath = 'images'; // Adjust the path as needed

// Function to check if a product is in the cart
function get_cart_item($product_id, $customer_id) {
    $table = 'salesdetails';
    $where = 'product_id';
    $data = $product_id;
    $additional_where = 'customer_id';
    $additional_data = $customer_id;
    
    $cart_item = get_record_with_additional_where($table, $where, $data, $additional_where, $additional_data);

    return $cart_item;
}

// Function to get a record with an additional WHERE condition
function get_record_with_additional_where($table, $where, $data, $additional_where, $additional_data) {
    $row = null;
    $sql = "SELECT * FROM $table WHERE $where = ? AND $additional_where = ?";
    $conn = dbconnect();
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$data, $additional_data]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC); // Use fetch instead of fetchAll
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
    $conn = null;
    return $row; // Return a single record, not an array of records
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add-to-cart"])) {
    $product_id = $_POST["product_id"];
    $customer_id = $_SESSION['user_id'];
    $quantity = "1";

    if (isset($_POST["selected_flower_types"]) && isset($_POST["selected_ribbon_colors"]) && isset($_POST["message"])) {
        $selected_flower_types = $_POST["selected_flower_types"];
        $selected_ribbon_colors = $_POST["selected_ribbon_colors"];
        $message = $_POST["message"];
        update_or_add_cart_item($product_id, $customer_id, $selected_flower_types, $selected_ribbon_colors, $message, $quantity);
    } else {
        add_cart_item_without_optional_fields($product_id, $customer_id, $quantity);
    }
}


function update_or_add_cart_item($product_id, $customer_id, $flower_types, $ribbon_colors, $message, $quantity) {
    $conn = dbconnect(); // Ensure you have a function to connect to your database

    // Provide default values if no customizations are selected
    $flower_types = $flower_types ?: ''; // Default to 'None' if empty
    $ribbon_colors = $ribbon_colors ?: ''; // Default to 'None' if empty
    $message = $message ?: ''; // Default to 'No Message' if empty

    // Convert strings to arrays if necessary
    if (is_string($flower_types)) {
        $flower_types = explode(',', $flower_types);
    }
    if (is_string($ribbon_colors)) {
        $ribbon_colors = explode(',', $ribbon_colors);
    }

    // Sort flower types and ribbon colors
    sort($flower_types);
    sort($ribbon_colors);

    // Convert arrays back to strings for storage and comparison
    $flower_types_str = implode(",", $flower_types);
    $ribbon_colors_str = implode(",", $ribbon_colors);

    // Check if the same product with the same attributes is already in the cart
    $sql = "SELECT * FROM salesdetails WHERE product_id = ? AND customer_id = ? AND flower_type = ? AND ribbon_color = ? AND message = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$product_id, $customer_id, $flower_types_str, $ribbon_colors_str, $message]);
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart_item) {
        // Same product with same attributes exists, update quantity
        $update_sql = "UPDATE salesdetails SET quantity = quantity + :quantity WHERE product_id = :product_id AND customer_id = :customer_id AND flower_type = :flower_types AND ribbon_color = :ribbon_colors AND message = :message";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->execute([
            ':quantity' => $quantity,
            ':product_id' => $product_id,
            ':customer_id' => $customer_id,
            ':flower_types' => $flower_types_str,
            ':ribbon_colors' => $ribbon_colors_str,
            ':message' => $message
        ]);
    } else {
        // Either product is not in the cart or attributes are different, add a new cart item
        $insert_sql = "INSERT INTO salesdetails (product_id, customer_id, flower_type, ribbon_color, message, quantity) VALUES (:product_id, :customer_id, :flower_types, :ribbon_colors, :message, :quantity)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->execute([
            ':product_id' => $product_id,
            ':customer_id' => $customer_id,
            ':flower_types' => $flower_types_str,
            ':ribbon_colors' => $ribbon_colors_str,
            ':message' => $message,
            ':quantity' => $quantity
        ]);
    }

    // Close the database connection if necessary
    // $conn = null;
}


function add_cart_item_without_optional_fields($product_id, $customer_id, $quantity) {
    $conn = dbconnect(); // Ensure you have a function to connect to your database

    // Check if the product is already in the cart
    $sql = "SELECT * FROM salesdetails WHERE product_id = ? AND customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$product_id, $customer_id]);
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart_item) {
        // Product exists, update quantity
        $update_sql = "UPDATE salesdetails SET quantity = quantity + :quantity WHERE product_id = :product_id AND customer_id = :customer_id";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->execute([
            ':quantity' => $quantity,
            ':product_id' => $product_id,
            ':customer_id' => $customer_id
        ]);
    } else {
        // Product is not in the cart, add a new cart item
        $insert_sql = "INSERT INTO salesdetails (product_id, customer_id, quantity) VALUES (:product_id, :customer_id, :quantity)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->execute([
            ':product_id' => $product_id,
            ':customer_id' => $customer_id,
            ':quantity' => $quantity
        ]);
    }
}




?>



<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product</title>
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
.cart {
	font-size: 30px;
	padding: 0;
	color: #65a5a5;
	margin-top: -5px;
	left: 50.5%;
	transform: translateX(-50%);
	position: absolute;
}
.num-cart {
	background-color: #ff7e95;
	border-radius: 50px;
	padding: 2px;
	margin: auto;
	width: 20px;
	height: 20px;
	font-size: 10px;
	position: absolute;
	top: 5px;
	margin-left: 20.5%;
	transform: translateX(50%);
	color: white;
	text-align: center;
}
.nav-hr {
	width: 60%;
	margin: auto;
	margin-top: -6px;
}
#search-results {
	display: none;
}
.back {
	display: none;
}
.container {
	display: flex;
	justify-content: space-between;
	gap: 20px;
}
.image-container {
	max-width: 99%;

}
.carousel {
	background-color: #f5f5f5;
}
.carousel-control-prev-icon, .carousel-control-next-icon {
	color: #000000;
	margin-top: 10%;
}
.carousel-item {
	height: 500px;
}
.carousel-item img {
	width: 600px;
	height: 550px;
}
.carousel-indicators li {
	background-color: transparent;
	width: 7px;
	height: 7px;
	border: 1px solid #666;
	border-radius: 50%;
	margin-right: 5px;
}
.carousel-indicators .active {
	background-color: #666;
}
.column1 {
	flex-basis: 50%;
	margin-top: 10px;
	display: flex;
	flex-direction: column;
	align-items: center;
}
.image-grid {
	display: flex;
	flex-wrap: wrap;
	justify-content: center;
	align-items: center;
	margin-left: 0px;
}
.image-row {
	margin-top: 1px;
	justify-content: flex-start;
}
.image-row img {
	width: 295px;
	height: 330px;
	margin-right: 1px;
	margin-top: 5px;
}
.column2 {
	flex-basis: 50%;
}
.p-name {
	font-weight: 500;
	margin-top: 15px;
	margin-bottom: 5px;
	font-size: 17px;
	color: #555;
}
.p-category {
	margin-top: 10px;
	margin-bottom: 5px;
	font-size: 15px;
	color: #666;
}
.p-price {
	margin-top: 10px;
	margin-bottom: 5px;
	font-size: 17px;
	color: #666;
	font-weight: 500;
}
.p-ratings {
	margin-top: 10px;
	margin-bottom: 5px;
	font-size: 14px;
	color: #666;
}
.add-btn .add {
    background-color: #65A5A5;
    border: none;
    padding: 10px;
    width: 81%;
    color: white;
    border-radius: 10px;
    margin-top: 30px;
    font-size: 15px;
}
.flower-btn,
.ribbon-btn {
    align-items: center;
    padding: 5px 9px;
    border-radius: 10px;
    border: 1px solid #65A5A5;
    background: #FFF;
    color: #666;
    margin: 5px 6px;
    cursor: pointer;
    width: auto;
    font-size: 14px;
}

.flower-btn:focus,
.ribbon-btn:focus,
.selected-btn {
    outline: none;
    border: none;
    background-color: #65A5A5;
    color: white;
}

.add-btn .add:focus {
	border-color: #65A5A5;
	outline: none;
}
.add-btn .add:hover {
	color: #f0f0f0;
	background-color: #65a5a5ec;
}
.add-btn .messenger {
	border: 1px solid #65A5A5;
	background-color: transparent;
	padding: 5px;
	width: 10%;
	color: #65A5A5;
	border-radius: 10px;
	margin-top: 40px;
	margin-left: 10px;
    font-size: 20px;
}
.add-btn .messenger:focus {
	border-color: #65A5A5;
	outline: none;
}
.add-btn .messenger:hover {
	color: #65a5a5c8;
	border-color: #65a5a5c8;
}
/* Style for modals */

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
	background-color: #65a5a5e1;
	margin: 20% auto;
	padding: 20px;
	border: none;
	border-radius: 10px;
	max-width: 300px;
	text-align: center;
	color: white;
}
.bi-check-circle {
	font-size: 50px;
	color: white;
	margin: auto;
	margin-top: 5%;
}
.border {
	display: none;
	height: 8px;
	background-color: #F0F0F0;
	outline: none;
	margin-top: 45px;
	border: none;
}
.review-text {
	margin-top: 10px;
	/* Adjust the margin-top as needed */
}
.p-desc-label {
	margin-top: 40px;
	font-weight: 500;
	font-size: 16px;
	color: #666;
}
.p-desc {
	margin-top: 25px;
	font-size: 15px;
	text-align: justify;
	line-height: .6cm;
	width: 95%;
    color:#666;
}
.shop {
	display: flex;
	align-items: center;
	background-color: #f0f0f0;
	padding: 15px;
	border-radius: 10px;
	width: 95%;
	margin-top: 35px;
}
.messenger {
	padding: 5px;
	border-radius: 4px;
	border: none;
	outline: none;
	background-color: #65a5a5;
	color: white;
}
.view-shop {
	padding: 10px;
	border-radius: 4px;
	border: none;
	outline: none;
	background-color: #65a5a5;
	color: white;
	font-size: 12px;
}
.view-shop i {
	margin-right: 10px;
}
.shop-pic img {
	width: 60px;
	height: 60px;
	margin-right: 10px;
	border-radius: 50px;
	position: relative;
	top: -10px;
}
.shop-info {
	flex-direction: column;
	font-size: 13px;
	line-height: 5%;
	margin-top: 2%;
	position: relative;
}
.reviews {
	margin-top: 40px;
}
.r-label {
	margin-top: 40px;
	font-weight: 500;
	font-size: 16px;
	color: #666;
	display: inline;
}
.all p {
	margin-left: 45%;
	margin-top: 25px;
	font-size: 14px;
	color: #666;
}
.no_feedback{
    margin-left: 37%;
	margin-top: 25px;
	font-size: 14px;
	color: #666;
}
.all:hover {
	color: #888;
	text-decoration: none !important;
}
.stars .fa {
	color: gold;
	font-size: .8em;
	margin-right: 3px;
}
.stars {
	display: flex;
	margin-top: 15px;
}
.stars p {
	margin-left: 5px;
	margin-top: -4px;
	font-size: 13px;
}
.p-review {
	display: flex;
	align-items: center;
	/* Vertically align items */
}
.review-pic img {
	width: 35px;
	height: 35px;
	margin-right: 10px;
	border-radius: 50px;
	position: relative;
	top: -10px;
}
.review-info {
	flex-direction: column;
	font-size: 13px;
	line-height: 5%;
	margin-top: 2%;
	position: relative;
}
.review-text {
	margin-top: 10px;
	/* Adjust the margin-top as needed */
}
.r-date {
	font-size: 11px;
}
.r-star .fa {
	color: gold;
	font-size: .8em;
	margin-right: 1px;
}
.c-review {
	width: 85%;
	text-align: justify;
	margin-left: 9%;
	margin-top: 10px;
	font-size: 13px;
}
/* Image preview */

.image-preview {
	display: flex;
	flex-wrap: nowrap;
	margin-left: 40px;
	margin-top: 20px;
	padding: 0px;
}
.image-preview img {
	width: 80px;
	height: 70px;
	margin-right: 5px;
	align-items: flex-start;
	padding: 0px;
	border-radius: 5px;
}
/* Add this CSS to your stylesheet */

.modal1 {
	display: none;
	position: fixed;
	z-index: 1;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	background-color: rgba(0, 0, 0, 0.5);
}
.modal1-content {
	margin: auto;
	display: block;
	width: 70%;
	height: 90%;
	background-color: transparent;
	margin-top: 30px;
}
.close {
	position: absolute;
	top: 20px;
	right: 30px;
	font-size: 30px;
	cursor: pointer;
	color: white;
}

.type-label {
	font-size: 15px;
	color: #666;
	margin-top: 6%;
	margin-bottom: 4%;
}


.ribbon-label {
	font-size: 15px;
	color: #666;
	margin-top: 6%;
	margin-bottom: 4%;
}


.m-label {
	font-size: 16px;
	color: #666;
	margin-top: 6%;
	margin-bottom: 4%;
}
.p-message{
    margin-top: 6%;
}
.p-message input {
    display: block; /* Set input to block, making it appear on a new line */
    border-radius: 10px;
    color: #666;
    border: 1px solid #bebebe;
    width: 95%;
    padding: 5px;
    margin-bottom: 10px; /* Add some bottom margin for spacing */
}
.message {
	border: 1px solid #65A5A5;
	padding: 5px;
	width: 95%;
	height: 100px;
       
  
}
.message::placeholder {
    font-size: 15px;
    color: #1010108f;
    position: absolute;
    top: 35px;
    left: 5px; 
    width:90%;
    text-align: justify;
    word-wrap: break-word;  
}


.message:focus {
	margin-top: 0px;
	outline-color: #65A5A5;
}
.other {
	color: #666;
	font-size: 15px;
	font-style: normal;
	font-weight: 500;
	line-height: normal;
	margin-left: 20%;
	margin-top: 60px;
	margin-bottom: 20px;
	display: flex;
	justify-content: space-between;
	align-items: center;
}
.product-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    gap: 20px;
    max-width: 1140px;
    margin: 0 auto;
    margin-top: 20px;
}

.product {
    flex: 0 0 calc(4%);
    padding: 10px;
    border: 1px solid #ddd;
    display: flex;
    flex-direction: column;
    text-align: left;
    box-sizing: border-box;
}

.product a:hover {
    text-decoration: none;
}

.product:hover {
    transform: scale(1.05);
}

.product a img {
    width: 150px;
    height: 150px;
    flex-grow: 1;
}

.product .product-info {
    padding: 10px;
}

.product .product-name {
    margin-top: 20px;
    width: 150px;
    margin-bottom: 5px;
    font-size: 15px;
    color: #666;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

 .product .product-category {
     color: #666;
     margin-bottom: 5px;
     font-size: 13px;
}
 .product .product-price {
     color: #666;
     margin-bottom: 5px;
     font-size: 13px;
 }

.product .p {
	display: flex;
}
.p-end {
	color: #bebebe;
	font-size: 14px;
	text-align: center;
	margin-top: 30px;
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
	.nav-hr {
		width: 100%;
	}
	a #search-results{
		display: block ;
		font-size: 15px;
		margin-left: 20px;
		color: #555;
		
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
	.cart {
		font-size: 20px;
		padding: 0;
		position: absolute;
		top: 25px;
		left: 90%;
		transform: translateX(-50%);
		color: #65a5a5;
	}
	.num-cart {
		background-color: #ff7e95;
		border-radius: 50px;
		padding: 2px;
		margin: auto;
		width: 17px;
		height: 17px;
		font-size: 9px;
		font-weight: bold;
		position: absolute;
		top: 2px;
		margin-left: 10%;
		transform: translateX(50%);
		color: white;
		text-align: center;
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
		display: flex;
		flex-direction: column;
		gap: 20px;
		margin-top: 60px;
	}
	.column1 {
		display: flex;
		flex-direction: column;
		align-items: center;
		margin: 0;
		margin-left: 0;
	}
	.image-container {
		max-width: 100%;
		margin: auto;
		background-color: white;
	}
	.carousel {
		background-color: white;
		width: 108.5%;
		margin-left: -15px;
	}
	.carousel-item {
		height: 450px;
	}
	.carousel-item img {
		width: 100%;
		height: 100%;
	}
	.image-grid {
		display: none;
	}
	.carousel-indicators li {
		background-color: transparent;
		width: 5px;
		height: 5px;
		border: 1px solid #666;
		border-radius: 50%;
		margin-right: 5px;
	}
	.carousel-indicators .active {
		background-color: #666;
	}
	.p-name {
		font-weight: 500;
		font-size: 14px;
		color: #555;
		display: inline;
	}
	.p-category {
		margin-top: 10px;
		margin-bottom: 5px;
		font-size: 13px;
		color: #666;
	}
	.p-price {
		margin-top: 10px;
		margin-bottom: 5px;
		font-size: 14px;
		color: #666;
		font-weight: 500;
	}
	.p-ratings {
		margin-top: 10px;
		margin-bottom: 5px;
		font-size: 12px;
		color: #666;
	}
    .flower-btn,
    .ribbon-btn {
        align-items: center;
        padding: 5px 9px;
        border-radius: 10px;
        border: 1px solid #65A5A5;
        background: #FFF;
        color: #666;
        margin: 5px 6px;
        cursor: pointer;
        width: auto;
        font-size: 12px;
    }

    .flower-btn:focus,
    .ribbon-btn:focus,
    .selected-btn {
        outline: none;
        border: none;
        background-color: #65A5A5;
        color: white;
    }

    .type-label {
        font-size: 13px;
        color: #666;
        margin-top: 6%;
        margin-bottom: 4%;
    }


    .ribbon-label {
        font-size: 13px;
        color: #666;
        margin-top: 6%;
        margin-bottom: 4%;
    }

	.btn-container {
		position: fixed;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 70px;
		background-color: #fff;
		box-shadow: 0px -5px 5px rgba(0, 0, 0, 0.1);
		z-index: 1;
	}
	.add-btn {
		display: flex;
		justify-content: space-around;
		padding: 2px;
	}
	.add-btn .add {
		background-color: #65A5A5;
		border: none;
		margin-top: 3%;
		margin-left: 4%;
		color: white;
		border-radius: 20px;
	}
	.add-btn button.messenger {
		border: 1px solid #65A5A5;
		background-color: transparent;
		padding: 9px;
		width: 50px;
		color: #65A5A5;
		border-radius: 10px;
		margin-top: 12px;
		margin-left: 20px;
		margin-right: 15px;
	}
	.add-btn .messenger:focus {
		border-color: #65A5A5;
		outline: none;
	}
	.add-btn .messenger:hover {
		color: #65a5a5c8;
		border-color: #65a5a5c8;
	}
	.border {
		display: none;
		height: 8px;
		background-color: #F0F0F0;
		outline: none;
		margin-top: 45px;
		border: none;
	}
	.p-desc-label {
		margin-top: 25px;
		font-weight: 500;
		font-size: 13px;
		color: #555;
	}
	.p-desc {
		margin-top: 20px;
		font-size: 12px;
		text-align: justify;
		line-height: .6cm;
		width: 100%;
		color: #666;
	}
	.shop {
		display: flex;
		align-items: center;
		background-color: #f0f0f0;
		padding: 15px;
		border-radius: 10px;
		width: 100%;
		margin-top: 25px;
	}
	.view-shop {
		padding: 8px;
		border-radius: 4px;
		border: none;
		outline: none;
		background-color: #65a5a5;
		color: white;
		font-size: 11px;
	}
	.view-shop:focus {
		outline: none;
	}
	.view-shop i {
		margin-right: 10px;
	}
	.shop-pic img {
		width: 50px;
		height: 50px;
		margin-right: 10px;
		border-radius: 50px;
		position: relative;
		top: -15px;
	}
	.shop-info {
		flex-direction: column;
		font-size: 12px;
		line-height: 5%;
		margin-top: 3%;
		position: relative;
	}
	.reviews {
		margin-top: 30px;
	}
	.r-label {
		margin-top: 40px;
		font-weight: 500;
		font-size: 13px;
		color: #555;
		display: inline;
	}
	.all p {
		margin-left: 35%;
		margin-top: 20px;
		font-size: 12px;
		color: #666;
	}
	.stars .fa {
		color: gold;
		font-size: .7em;
		margin-right: 3px;
	}
	.stars {
		display: flex;
		margin-top: 15px;
	}
	.stars p {
		margin-left: 5px;
		margin-top: -4px;
		font-size: 11px;
		color: #666;
	}
	.p-review {
		display: flex;
		align-items: center;
	}
	.review-pic img {
		width: 30px;
		height: 30px;
		margin-right: 10px;
		border-radius: 50px;
		position: relative;
		top: -10px;
	}
	.review-info {
		flex-direction: column;
		font-size: 12px;
		line-height: 5%;
		margin-top: 2%;
		position: relative;
	}
	.review-text {
		margin-top: 10px;
	}
	.r-date {
		font-size: 11px;
	}
	.r-star .fa {
		color: gold;
		font-size: .8em;
		margin-right: 1px;
	}
	.c-review {
		width: 85%;
		text-align: justify;
		margin-left: 13%;
		margin-top: 10px;
		font-size: 11px;
	}
    .no_feedback{
        margin-left: 24%;
		margin-top: 20px;
		font-size: 12px;
    }
	/* Image preview */
	.image-preview {
		display: flex;
		flex-wrap: nowrap;
		margin-left: 40px;
		margin-top: 20px;
		padding: 0px;
	}
	.image-preview img {
		max-width: 60px;
		height: 50px;
		margin-right: 3px;
		align-items: flex-start;
		padding: 0px;
		border-radius: 5px;
	}
	/* Add this CSS to your stylesheet */
	.modal1 {
		display: none;
		position: fixed;
		z-index: 200;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.5);
	}
	.modal1-content {
		margin: auto;
		display: block;
		width: 80%;
		height: 50%;
		background-color: transparent;
		margin-top: 50%;
	}
	.close {
		position: absolute;
		top: 15%;
		right: 30px;
		font-size: 20px;
		cursor: pointer;
		color: white;
	}
	.prev, .next {
		position: absolute;
		top: 50%;
		transform: translateY(-50%);
		background-color: transparent;
		color: white;
		border: none;
		padding: 10px;
		font-size: 20px;
		cursor: pointer;
   }
	.prev {
		left: 10px;
   }
	.next {
		right: 10px;
   }
   .prev:focus {
	   outline: none;
  }
   .next:focus {
	   outline: none;
  }

	.t-btn {
		align-items: center;
		padding: 4px 9px;
		border-radius: 10px;
		border: 1px solid #65A5A5;
		background: #FFF;
		color: #666;
		margin: 5px 6px;
		cursor: pointer;
		width: auto;
		font-size: 13px;
		margin-left: 0px;
	}
	.t-btn:focus {
		outline: none;
		border: none;
		background-color: #65A5A5;
		color: white;
	}

	.ribbon-btn {
		align-items: center;
		padding: 4px 9px;
		border-radius: 10px;
		border: 1px solid #65A5A5;
		background: #FFF;
		color: #666;
		margin: 5px 6px;
		cursor: pointer;
		width: auto;
		font-size: 13px;
		margin-left: 0px;
	}
	.ribbon-btn:focus {
		outline: none;
		border: none;
		background-color: #65A5A5;
		color: white;
	}
	.m-label {
		font-size: 15px;
		color: #555;
		margin-top: 6%;
		margin-bottom: 4%;
	}
	.p-message input {
		border-radius: 10px;
		color: #666;
		border: 1px solid #bebebe;
		padding: 5px;
		width: 100%;
		height: 100px;
		margin-top: 2%;
        font-size: 13px;
	}
	.message {
		border: 1px solid #65A5A5;
		padding: 5px;
		width: 100%;
		height: 80px;
		margin-top: 2%;
	}
	.message::placeholder {
		font-size: 13px;
		color: #1010108f;
		text-align: left;
        margin-top: 1%;
	}
	.message:focus {
		margin-top: 2%;
		outline-color: #65A5A5;
	}
	.modal {
		display: none;
		position: fixed;
		z-index: 1;
		left: 0;
		top: 0;
		width: 100%;
		overflow: auto;
	}
	.modal-content {
		background-color: #65a5a5e1;
		margin: 90% auto;
		padding: 10px;
		border: none;
		border-radius: 10px;
		max-width: 140px;
		max-height: 90px;
		text-align: center;
		color: white;
		font-size: 12px;
	}
	.bi-check-circle {
		font-size: 30px;
		color: white;
		margin: auto;
		margin-top: 3%;
	}

	.other {
		color: #555;
		font-size: 13px;
		font-style: normal;
		font-weight: 500;
		line-height: normal;
		margin-left: 5.5%;
		margin-top: 10px;
		margin-bottom: 0;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.product-list {

         display: flex;
         flex-wrap: wrap;
         justify-content: center;
    }
     .product {
         flex: 0 0 calc(2%);
         padding: 10px;
         border: 1px solid #ddd;
  
         display: flex;
         flex-direction: column;
         text-align: left;
         box-sizing: border-box;
    }
     .product img a{
         width: 193px;
         height:150px;
         flex-grow: 1;
    }

     .product .product-name {
        font-size: 13px;
        color: #666;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    
    }
     .product .product-category {
         color: #666;
         margin-bottom: 5px;
         font-size: 12px;
    }
     .product .product-price {
         color: #666;
         margin-bottom: 5px;
         font-size: 13px;
    }
     .product .product-ratings {
         color: #acaaaa;
         font-size: 11px;
         margin-top: 3px;
         margin-left: 45px;
    }
     .product .p {
         display: flex;
    }
     .p-end {
         color: #bebebe;
         font-size: 12px;
         text-align: center;
         margin-top: 10px;
         margin-bottom: 40px;
    }
	/* Media query to adjust alignment for smaller screens */
	@media (max-width: 768px) {
		.product {
			width: calc(50% - 15px);
		}
		.product-list {
			gap: 10px;
		}
		.btn-container .btn:nth-child(n+4) {
			display: none;
		}
		.container {
			flex-direction: column;
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
                        <a href="cart.php">
                            <li class="cart">
                                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                <p class="num-cart">1 </p>
                            </li>
                        </a>
                        <li class="nav-item">
                            <form class="form-inline my-2 my-lg-0">
                                <a href=""><i class="fa fa-search"></i></a>
                                <input type="text"  class="form-control form-input" placeholder="Search">
                                <a href="javascript:void(0);" onclick="goBack()">
                                    <i class="back fa fa-angle-left" aria-hidden="true"></i>
                                    <div id="search-results"></div>
                                  </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <main class="main">
            <div class="container">
                <div class="column1 mx-auto text-center">
                    <div class="image-container">
                        <div id="myCarousel" class="carousel slide" >
                            <!-- Indicators -->
                            <ol class="carousel-indicators">
                                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                            </ol>
                            <!-- Slides -->
                            <div class="carousel-inner">
                                <div class="carousel-item active" data-target="image1">
                                <?php
                                    // Extract the first image URL from the comma-separated string
                                    $imageUrls = explode(',', $product['product_img']);
                                    $firstImageUrl = trim($imageUrls[0]); // Get the first image URL

                                    // Display the first image
                                    echo '<img src="' . $firstImageUrl . '" alt="' . $product['product_name'] . '">';
                                ?>                                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column2">
                    <!-- Second column with image descriptions -->
                    <p class="p-name"><?php echo $product['product_name']; ?></p>
                    <p class="p-category"><?php echo $product['product_category']; ?></p>
                    <div class="p-price"><?php echo '₱ '. $product['product_price']; ?></div>
                    <p class="p-ratings">4.5 ratings & 35 reviews</p>
                    <form method="POST" action=""id="myForm">
                        <?php if ($isArranger): ?>
                            <div class="f-type">
                                <h4 class="type-label">Flower Type(s)</h4>
                                <?php
                                if (!empty($product['flower_type'])) {
                                    $flowerTypes = explode(',', $product['flower_type']);
                                    foreach ($flowerTypes as $type) {
                                        echo '<button type="button" class="flower-btn" onclick="toggleSelection(\'flower\', \'' . htmlspecialchars(trim($type)) . '\')">' . htmlspecialchars(trim($type)) . '</button>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="ribbon">
                                <h4 class="ribbon-label">Ribbon Color</h4>
                            <?php
                                    if (!empty($product['ribbon_color'])) {
                                        $ribbonColors = explode(',', $product['ribbon_color']);
                                        foreach ($ribbonColors as $color) {
                                            echo '<button type="button" class="ribbon-btn" onclick="toggleSelection(\'ribbon\', \'' . htmlspecialchars(trim($color)) . '\')">' . htmlspecialchars(trim($color)) . '</button>';
                                        }
                                    }
                                ?>
                            </div>
                            <div class="p-message">
                            <h4 class="ribbon-label">Message</h4>
                                <input type="text" class="message" name="message" placeholder="Type your message here...">
                            </div>                       
                        <?php endif; ?>
                            <div class="btn-container">
                                <div class="add-btn">
                                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                    <!-- Hidden fields for selected flower types and ribbon colors -->
                                    <input type="hidden" name="selected_flower_types" id="selected_flower_types" value="">
                                    <input type="hidden" name="selected_ribbon_colors" id="selected_ribbon_colors" value="">
                                    <button class="add add-to-cart-button" name="add-to-cart" data-product-id="<?= $product['product_id'] ?>">Add to Cart</button>
                        </form>
                   
                                <a href="chat.php?user_id=<?php echo $user['user_id']; ?>">
                                    <button class="messenger"><i class="bi bi-messenger"></i></button>
                                </a>
                        </div>
                            <div id="addModal" class="modal">
                                <div class="modal-content">
                                    <i class="bi bi-check-circle"></i>
                                    <p class="confirm-order">Added to Cart</p>
                                </div>
                            </div>
                    </div>
                        <p class="p-desc-label">Description</p>
                        <p class="p-desc"><?php echo $product['product_desc']; ?></p>   
                    
                    <div class="border"></div>
                    <?php 
                         // Retrieve shop details from the database based on shop_id
                        $shop = get_record('shops', 'shop_id', $product['shop_owner']);

                        // Display shop details
                        if ($shop) {
                            echo '<div class="shop">';
                            echo '<div class="shop-pic">';
                            echo '<img src="' . $shop['shop_img'] . '" alt="Shop Image">';
                            echo '</div>';
                            echo '<div class="shop-info">';
                            echo '<div class="info">';
                            echo '<p class="s-name">' . $shop['shop_name'] . '</p>';
                            echo '<p class="s-location"><i class="bi bi-geo-alt"></i> ' . $shop['shop_address'] . '</p>';
                            echo '<a href="../vendor/vendor_shop.html">';
                            echo '<button class="view-shop"><i class="bi bi-shop-window"></i>View Shop</button>';
                            echo '</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        } else {
                            echo "Shop details not found.";
                        }
                    ?>
                    <div class="reviews">
                        <p class="r-label">Product Ratings</p>
                    </div>
                    <div class="stars">
                    <p class="r_stars">4.5 ratings & 35 Reviews&nbsp;</p> 
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        
                    </div>
                    <?php
                    function get_product_details($product_id) {
                        $conn = dbconnect();
                        $sql = "SELECT * FROM products WHERE product_id = ?";
                        try {
                            $stmt = $conn->prepare($sql);
                            $stmt->execute([$product_id]);
                            $product_details = $stmt->fetch(PDO::FETCH_ASSOC);
                            $conn = null;
                            return $product_details;
                        } catch (PDOException $e) {
                            echo $sql . "<br>" . $e->getMessage();
                            $conn = null;
                            return false;
                        }
                    }
                        
                    if (isset($_GET['product_id'])) {
                        $product_id = $_GET['product_id'];
                    
                        // Fetch product details from the product table
                        $product_details = get_product_details($product_id);
                    
                        // Display the product details
                        if ($product_details) {
                            // ... (your existing code for displaying product details)
                    
                            // Display feedback and ratings
                            $feedbackAndRatings = get_feedback_and_ratings($product_id);
                    
                            if ($feedbackAndRatings) {
                                foreach ($feedbackAndRatings as $feedback) {
                                    // Fetch customer details
                                    $customer = get_customer_details($feedback['customer_id']);
                                    $fullName = $customer['first_name'] . ' ' . $customer['last_name'];
                                    $reviewImagePath = '../images/' . $feedback['review_image'];
                                    // Display customer details if there are feedback and ratings
                                    if ($customer && $feedback['rating'] > 0) {
                                        echo '<div class="p-review">
                                                <div class="review-pic">
                                                    <img src="' . $customer['profile_img'] . '" alt="Customer Profile">
                                                </div>
                                                <div class="review-info">
                                                    <div class="review-text">
                                                        <p class="c-name">' . $fullName . '</p>
                                                        <p class="r-star">' . generate_star_rating($feedback['rating']) . '&nbsp;' . $feedback['rating'] . '&nbsp;stars</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="r-details">
                                                <p class="c-review">' . $feedback['feedback'] . '</p>';
                    
                                        // Display review image if available
                                        if (!empty($feedback['review_image'])) {
                                            echo '<div class="image-preview">
                                                    <img src="' . $reviewImagePath . '" alt="Review Image">
                                                </div>';
                                        }
                                        echo '</div>';
                                    } else {
                                        // If there are no feedback and ratings or the rating is 0, don't display user details
                                        echo '<div class="no_feedback">No feedback and ratings yet.</div>';
                                    }
                                }
                            } else {
                                echo '<div class="no_feedback">No feedback and ratings yet.</div>';
                            }
                        } else {
                            echo "Product details not found.";
                        }
                    } else {
                        echo "Product ID not provided.";
                    }
                            // Function to fetch feedback and ratings from the sales table
                            function get_feedback_and_ratings($product_id) {
                                $conn = dbconnect();
                                $sql = "SELECT * FROM sales WHERE product_id = ?";
                                try {
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute([$product_id]);
                                    $feedbackAndRatings = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    $conn = null;
                                    return $feedbackAndRatings;
                                } catch (PDOException $e) {
                                    echo $sql . "<br>" . $e->getMessage();
                                    $conn = null;
                                    return false;
                                }
                            }

                            // Function to fetch customer details
                            function get_customer_details($customer_id) {
                                $conn = dbconnect();
                                $sql = "SELECT * FROM users WHERE user_id = ?";
                                try {
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute([$customer_id]);
                                    $customerDetails = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $conn = null;
                                    return $customerDetails;
                                } catch (PDOException $e) {
                                    echo $sql . "<br>" . $e->getMessage();
                                    $conn = null;
                                    return false;
                                }
                            }

                            // Function to generate star rating HTML based on the rating value
                            function generate_star_rating($rating) {
                                $starRatingHTML = '<i class="fa fa-star" aria-hidden="true"></i>';
                                $emptyStarHTML = '<i class="fa fa-star-o" aria-hidden="true"></i>';

                                $fullStars = floor($rating);
                                $emptyStars = 5 - $fullStars;

                                $ratingHTML = str_repeat($starRatingHTML, $fullStars) . str_repeat($emptyStarHTML, $emptyStars);

                                return $ratingHTML;
                            }
                            ?>

                    <hr>
                    <a href="../common/see_all_reviews.html" class="all">
                        <p>See All Reviews</p>
                    </a>
                </div>
                <!-- Add this to your HTML -->
                <div id="imageModal" class="modal1">
                    <span class="close">&times;</span>
                    <img id="modalImage" class="modal1-content" alt="Modal Image">
                    <button id="prevButton" class="prev">&#8249;</button>
                    <button id="nextButton" class="next">&#8250;</button>
                </div>
            </div>
            <section>
                <div class="label">
                    <p class="other">Other Products</p>
                </div>
                <div class="product-list" id="product-container">
                  
                <?php 
                    $counter = 0;
                    foreach ($products as $product):
                        if ($counter >= 30) {
                            break; // Stop the loop after displaying 6 products
                        }
                        ?>
                        <div class="product">
                            <a href="customer_product.php?product_id=<?php echo $product['product_id']; ?>">
                            <?php
                                echo '<img src="' . $product['product_img'] . '" alt="' . $product['product_name'] . '">';
                            ?>                        
                                <div class="product-name"><?php echo $product['product_name']; ?></div>
                                <div class="product-category"><?php echo $product['product_category']; ?></div>
                                <div class="p">
                                    <div class="product-price"><?php echo '₱ '. $product['product_price']; ?></div>
                                  
                                </div>
                            </a>
                        </div>
                        <?php
                        $counter++; // Increment counter for each displayed product
                    endforeach; ?>
                </div>
            </section>
            <br><br><br>
            
        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            // Global declaration of addOrderBtn, if it exists
                var addOrderBtn = document.getElementById("addOrderBtn");

                // Add event listeners to "Add to Cart" buttons
                const addToCartButtons = document.querySelectorAll(".add-to-cart-button");
                addToCartButtons.forEach(function(button) {
                    button.addEventListener("click", function() {
                        console.log("Button clicked");
                        addToCartAndShowModal();
                    });
                });

                if (addOrderBtn) {
                    // Modify the updateSelections function
                    function updateSelections() {
                        var selectedFlowerTypes = document.getElementById('selected_flower_types');
                        var selectedRibbonColors = document.getElementById('selected_ribbon_colors');
                        var messageInput = document.querySelector(".message");

                        // Ensure that the input fields are always submitted, even if empty
                        selectedFlowerTypes.value = selectedFlowerTypes.value || 'None';
                        selectedRibbonColors.value = selectedRibbonColors.value || 'None';
                        messageInput.value = messageInput.value || 'No Message';
                    }

                    // Add a click event listener to the "Add to Cart" button
                    addOrderBtn.addEventListener("click", function () {
                        captureMessageInput(); // Capture the message input
                        updateSelections();     // Update selections for flower and ribbon

                        // Submit the form
                        document.querySelector("form").submit();
                    });

                    // Event listener to show the modal when the button is clicked
                    addOrderBtn.addEventListener("click", () => {
                        addModal.style.display = "block";
                        
                        // Automatically close the modal after 2 seconds
                        setTimeout(() => {
                            addModal.style.display = "none";
                        }, 2000);
                    });
                }

                function toggleSelection(type, value) {
                        var selectedValues = document.getElementById(type === 'flower' ? 'selected_flower_types' : 'selected_ribbon_colors').value;
                        var valuesArray = selectedValues ? selectedValues.split(',') : [];

                        // Check if the value is already selected
                        var isSelected = valuesArray.includes(value);

                        if (isSelected) {
                            // Unselect the value by removing it from the array
                            valuesArray = valuesArray.filter(val => val !== value);
                        } else {
                            // Select the value by adding it to the array
                            valuesArray.push(value);
                        }

                        document.getElementById(type === 'flower' ? 'selected_flower_types' : 'selected_ribbon_colors').value = valuesArray.join(',');

                        // Toggle the class for the clicked button
                        var buttons = document.querySelectorAll('.' + type + '-btn');
                        buttons.forEach(function (button) {
                            if (button.textContent.trim() === value) {
                                button.classList.toggle('selected-btn', !isSelected); // Toggle the class based on the current selection status
                            }
                        });
                    }





            // Function to capture the message input
            function captureMessageInput() {
                var messageInput = document.querySelector(".message");
                var messageValue = messageInput.value;
                document.getElementById("message").value = messageValue;
            }

            // Get the modal and image elements
            var modal = document.getElementById('imageModal');
            var modalImage = document.getElementById('modalImage');
            var prevButton = document.getElementById('prevButton');
            var nextButton = document.getElementById('nextButton');
            var images = document.querySelectorAll('.image-preview img');
            var currentIndex = 0;

            // Function to open the modal and display the clicked image
            function openModal(index) {
                modal.style.display = 'block';
                modalImage.src = images[index].src;
                currentIndex = index;
            }

            // Function to close the modal
            function closeModal() {
                modal.style.display = 'none';
            }

            // Function to navigate to the previous image
            function prevImage() {
                if (currentIndex > 0) {
                    currentIndex--;
                    modalImage.src = images[currentIndex].src;
                }
            }

            // Function to navigate to the next image
            function nextImage() {
                if (currentIndex < images.length - 1) {
                    currentIndex++;
                    modalImage.src = images[currentIndex].src;
                }
            }

            // Add click event listeners to the images
            images.forEach(function (image, index) {
                image.addEventListener('click', function () {
                    openModal(index);
                });
            });

            // Add click event listener to the close button
            document.querySelector('.close').addEventListener('click', closeModal);

            // Add click event listeners to the next and previous buttons
            prevButton.addEventListener('click', prevImage);
            nextButton.addEventListener('click', nextImage);

            // Go back function
            function goBack() {
                window.history.back();
            }
        </script>

  
        <script>
                
            // Get the modal element
            const addModal = document.getElementById("addModal");
            
            // Get the "Add to Cart" button element
            const addOrderBtn = document.getElementById("addOrderBtn");
            
            // Event listener to show the modal when the button is clicked
            document.querySelectorAll(".add-to-cart-button").forEach(button => {
             button.addEventListener("click", function(event) {
            event.preventDefault(); // Prevent form submission

            // Gather data from form elements
            var formData = new FormData();
            formData.append('product_id', this.getAttribute('data-product-id'));
            formData.append('customer_id', "<?php echo $_SESSION['user_id']; ?>");
            formData.append('selected_flower_types', document.getElementById('selected_flower_types').value);
            formData.append('selected_ribbon_colors', document.getElementById('selected_ribbon_colors').value);
            formData.append('message', document.querySelector('.message').value);
            formData.append('add-to-cart', true); 
                // Send the data to a PHP script using fetch or AJAX
                fetch("../php/add_to_cart.php", {
                    method: "POST",
                    body: JSON.stringify({
                        productId: productId,
                        customerId: customerId,
                        flowerType: flowerType,
                        ribbonColor: ribbonColor,
                        message: message
                    }),
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Product added to cart successfully, show the modal
                        addModal.style.display = "block";

                        // Automatically close the modal after 2 seconds
                        setTimeout(() => {
                            addModal.style.display = "none";
                        }, 2000);
                    } else {
                        // Handle the case when adding to the cart fails
                        console.error("Failed to add the product to the cart.");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });
            });
        });
        </script>
        <script>
    // Add event listeners to "Add to Cart" buttons
    const addToCartButtons = document.querySelectorAll(".add-to-cart-button");
    addToCartButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            console.log("Button clicked");
            addToCartAndShowModal();
        });
    });
</script>
<script>
    function clearForm() {
        // Clear form fields
        document.getElementById("myForm").reset();
        
        // You may also want to reset the values of hidden fields if needed
        document.getElementById("selected_flower_types").value = "";
        document.getElementById("selected_ribbon_colors").value = "";
    }
</script>

      
            
    </body>
</html>
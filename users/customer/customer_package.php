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

// Retrieve package ID from the URL
$packageID = isset($_GET['package_id']) ? $_GET['package_id'] : null;

// Check if the package ID is valid
if ($packageID) {
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

    // Retrieve package details
    $packageDetails = get_package_details($packageID, $pdo);

    // Close the database connection
    $pdo = null;
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
	padding: 8px 15px;
	width: 15%;
	color: #65A5A5;
	border-radius: 10px;

	margin-left: 10px;
    font-size: 17px;
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
		top:87%;
		width: 100%;
		max-height: 120px;
		background-color: #fff;
		box-shadow: 0px -5px 5px rgba(0, 0, 0, 0.1);
		z-index: 1;
	}
	.add-btn {
		display: flex;
		justify-content: space-around;
		padding: 2px;
		margin-top: -15px;
		max-height: 80px;
		max-width: 95%;
		
	
	}
	.add-btn .add {
		background-color: #65A5A5;
		border: none;
		font-size: 13px;
		margin-left: 4%;
		color: white;
		border-radius: 10px;
		padding-bottom:10px;
	}
	.add-btn .messenger {
		border: 1px solid #65A5A5;
		background-color: transparent;
		padding-bottom: 10px;
		color: #65A5A5;
		border-radius: 10px;
		
		margin-left: 10px;
		margin-top: 30px;
	
		font-size: 20px;
	}
	.add-btn .i {
		border: 1px solid #65A5A5;
		background-color: transparent;	
		color: #65A5A5;
		border-radius: 10px;
		margin-top: -10px;
		font-size: 17px;
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
		 padding-bottom:50px;
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
                <div id="myCarousel" class="carousel slide">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    </ol>
                    <!-- Slides -->
                    <div class="carousel-inner">
                        <div class="carousel-item active" data-target="image1">
                            <!-- Display package image -->
                            <img src="<?php echo $packageDetails['package_image']; ?>" alt="<?php echo $packageDetails['package_name']; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="column2">
            <!-- Second column with package details -->
            <p class="p-name"><?php echo $packageDetails['package_name']; ?></p>
            <p class="p-price"><?php echo '₱ ' . $packageDetails['package_price']; ?></p>
            <p class="p-desc-label">Description</p>
            <p class="p-desc"><?php echo $packageDetails['inclusions']; ?></p>  
        </div>
    </div>

	
            <br><br><br>
            
        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        
<script>
    function clearForm() {
        // Clear form fields
        document.getElementById("myForm").reset();
        
        // You may also want to reset the values of hidden fields if needed
        document.getElementById("selected_flower_types").value = "";
        document.getElementById("selected_ribbon_colors").value = "";
    }
</script>
<script>
         function goBack() {
             window.history.back();
         }
       </script>  
      
            
    </body>
</html>
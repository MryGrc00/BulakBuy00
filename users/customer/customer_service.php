<?php
session_start();
include '../php/dbhelper.php';
include '../php/checksession.php';

if (isset($_GET['service_id']) && isset($_SESSION['user_id'])) {
    $serviceID = $_GET['service_id'];
    $userID = $_SESSION['user_id'];

    // Keep this line as per your requirement
    $services = get_services('services', 'users');

    // Connect to the database
    $pdo = dbconnect();

    // Retrieve service details
    $stmt = $pdo->prepare("SELECT * FROM services WHERE service_id = :serviceID");
    $stmt->execute(['serviceID' => $serviceID]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($service) {
        // Retrieve shop details based on arranger_id in the service record
        $stmt = $pdo->prepare("SELECT * FROM shops WHERE owner_id = :arrangerID");
        $stmt->execute(['arrangerID' => $service['arranger_id']]);
        $shop = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($shop) {
            // Retrieve user details (owner of the shop)
            $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :ownerID");
            $stmt->execute(['ownerID' => $shop['owner_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $user['user_id'] == $userID) {
                // Service, Shop, and User exist, and the shop belongs to the logged-in user
            } else {
            }
        } else {
            echo "Shop not found";
        }
    } else {
        echo "Service not found";
    }
}
?>




<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
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
     margin-top:5px;
}
 .column2 {
     flex-basis: 50%;
}
 .p-name{
     font-weight: 500;
     margin-top: 15px;
     margin-bottom: 5px;
     font-size: 20px;
     color: #666;
}
 .p-category{
     margin-top: 10px;
     margin-bottom: 5px;
     font-size: 17px;
     color: #666;
}
 .p-price{
     margin-top: 10px;
     margin-bottom: 5px;
     font-size: 21px;
     color: #666;
     font-weight: 500;
}
 .p-ratings{
     margin-top: 10px;
     margin-bottom: 5px;
     font-size: 14px;
     color: #666;
}
 .add-btn .add{
     background-color: #65A5A5;
     border: none;
     padding:10px;
     width:80%;
     color:white;
     border-radius:20px;
     margin-top: 30px;
}
 .add-btn .add:focus{
     border-color: #65A5A5;
     outline:none;
}
 .add-btn .add:hover{
     color:#f0f0f0;
     background-color: #65a5a5ec;
}
 .add-btn .messenger{
     border: 1px solid #65A5A5;
     background-color: transparent;
     padding:10px;
     width:10%;
     color:#65A5A5;
     border-radius:10px;
     margin-top: 40px;
     margin-left: 10px;
}
 .add-btn .messenger:focus{
     border-color: #65A5A5;
     outline:none;
}
 .add-btn .messenger:hover{
     color:#65a5a5c8;
     border-color: #65a5a5c8;
}
.bi-person-plus {
    font-size: 20px;
}
 .border{
     display:none;
     height:8px;
     background-color: #F0F0F0;
     outline:none;
     margin-top:45px;
     border:none;
}
 .review-text {
     margin-top: 10px;
    /* Adjust the margin-top as needed */
}
 .p-desc-label {
     margin-top:40px;
     font-weight: 500;
     font-size: 16px;
     color:#666;
}
 .p-desc{
     margin-top:25px;
     font-size: 14px;
     text-align: justify;
     line-height: .6cm;
     width:95%;
}
 .shop {
     display: flex;
     align-items: center;
     background-color: #f0f0f0;
     padding: 15px;
     border-radius: 10px;
     width:95%;
     margin-top:35px;
}
 .messenger{
     padding:5px;
     border-radius:4px;
     border: none;
     outline:none;
     background-color: #65a5a5;
     color: white;
}
 .view-shop{
     padding:10px;
     border-radius:4px;
     border: none;
     outline:none;
     background-color: #65a5a5;
     color: white;
     font-size:12px;
}
 .view-shop i{
     margin-right:10px;
}
 .shop-pic img{
     width: 60px;
     height: 60px;
     margin-right: 10px;
     border-radius:50px;
     position: relative;
     top: -10px;
}
 .shop-info {
     flex-direction: column;
     font-size:13px;
     line-height:5%;
     margin-top: 2%;
     position: relative;
}
 .reviews{
     margin-top: 40px;
}
 .r-label{
     margin-top:40px;
     font-weight: 500;
     font-size: 16px;
     color:#666;
     display: inline;
}
 .all p{
     margin-left: 45%;
     margin-top:25px;
     font-size: 14px;
     color: #666;
}
 .all:hover{
     color:#888;
     text-decoration: none !important;
}
 .stars .fa {
     color: gold;
     font-size: .8em;
     margin-right: 3px;
}
 .stars{
     display:flex;
     margin-top:15px;
}
 .stars p{
     margin-left:5px;
     margin-top:-4px;
     font-size:13px;
}
 .p-review {
     display: flex;
     align-items: center;
    /* Vertically align items */
}
 .review-pic img{
     width: 35px;
     height: 35px;
     margin-right: 10px;
     border-radius:50px;
     position: relative;
     top: -10px;
}
 .review-info {
     flex-direction: column;
     font-size:13px;
     line-height:5%;
     margin-top: 2%;
     position: relative;
}
 .review-text {
     margin-top: 10px;
    /* Adjust the margin-top as needed */
}
 .r-date{
     font-size: 11px;
}
 .r-star .fa {
     color: gold;
     font-size: .8em;
     margin-right: 1px;
}
 .c-review{
     width:85%;
     text-align: justify;
     margin-left: 9%;
     margin-top: 10px;
     font-size: 13px;
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
    width: 135px;
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
 .other{
     color: #666;
     font-size: 19px;
     font-style: normal;
     font-weight: 500;
     line-height: normal;
     margin-left: 21%;
     margin-top: 70px;
     margin-bottom: 0;
     display: flex;
     justify-content: space-between;
     align-items: center;
}
.service-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    gap: 20px;
    max-width: 1140px;
    margin: 0 auto;
    margin-top: 20px;
  }
  
  .service {
    flex: 0 0 calc(4%);
    margin-top:5px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    text-align: left;
    box-sizing: border-box;
  }
 .service a:hover{
    text-decoration: none;
    
 }
 .service:hover {
    transform: scale(1.05); 
    box-shadow: 0px 0px 6px #65a5a5; 
  }
 .service a img {
    width: 150px;
    height:150px;
    flex-grow: 1;
 }
 
 .service .service-info {
    padding: 10px;
 }
 
 .service .service-name {
    font-weight: 500;
    margin-top: 20px;
    margin-bottom: 5px;
    font-size: 15px;
    color: #666;
 }
 .service .service-category {
    color: #666;
    margin-bottom: 5px;
    font-size: 12px;
 }
 
 .service .service-price {
    color: #ff7e95;
    margin-bottom: 5px;
    font-size: 15px;
 }
 
 .service .service-ratings {
    color: #acaaaa;
    font-size: 11px;
    margin-top: 3px;
    margin-left: 30px;
 }
 .service .p {
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
        position: sticky;
        background-color: white;
        width:100%;
        z-index: 10; 
        top:-1px;
        
       
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
     .form-inline .back{
         text-decoration: none;
         color:#666;
    }
     .form-inline .fa-angle-left:focus {
         text-decoration: none;
         outline: none;
    }
    
    .container {
		display: flex;
		flex-direction: column;
		gap: 20px;
		margin-top: -5px;
        
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
     .p-name{
         font-weight: 500;
         font-size: 14px;
         color: #555;
         display: inline;
    }
     .p-category{
         margin-top: 10px;
         margin-bottom: 5px;
         font-size: 14px;
         color: #666;
    }
     .p-price{
         margin-top: 10px;
         margin-bottom: 5px;
         font-size: 13px;
         color: #666;
         font-weight: 500;
    }
     .p-ratings{
         margin-top: 10px;
         margin-bottom: 5px;
         font-size: 12px;
         color: #666;
    }
     
      .btn-container {
         position: fixed;
         bottom: 0;
         left: 0;
         width: 100%;
         height:70px;
         background-color: #fff;
         box-shadow: 0px -5px 5px rgba(0, 0, 0, 0.1); 
         z-index: 999; 
      }
      
     
      .add-btn {
         display: flex;
         justify-content: space-around;
         padding:2px;

        
      }
      .add-btn button.add {
         background-color: #65A5A5;
         border: none;
         width: 195%; 
         margin-top:8%;
         margin-left:1%;
         color: white;
         border-radius: 10px;
         font-size:14px;
       }
       


       .add-btn button.messenger{
         border: 1px solid #65A5A5;
         background-color: transparent;
         padding:10px;
         width: 50px;
         color:#65A5A5;
         border-radius:10px;
         margin-top: 12px;
         margin-left: 130px;
    }
     .add-btn .messenger:focus{
         border-color: #65A5A5;
         outline:none;
    }
     .add-btn .messenger:hover{
         color:#65a5a5c8;
         border-color: #65a5a5c8;
    }
    .nav-hr{
        width:100%;
        margin: auto;
        margin-top:30px;
   }
     .p-desc-label {
         margin-top:25px;
         font-weight: 500;
         font-size: 14px;
         color:#555;
    }
     .p-desc{
         margin-top:20px;
         font-size: 13px;
         text-align: justify;
         line-height: .6cm;
         width:100%;
         color:#666;
    }
    .shop {
      display: flex;
      align-items: center;
      background-color: #f0f0f0;
      padding: 15px;
      border-radius: 10px;
      width:100%;
      margin-top:25px;
 }

  .view-shop{
      padding:8px;
      border-radius:4px;
      border: none;
      outline:none;
      background-color: #65a5a5;
      color: white;
      font-size:11px;
 }
 .view-shop:focus{
   outline:none;
 }
  .view-shop i{
      margin-right:10px;
 }
  .shop-pic img{
      width: 50px;
      height: 50px;
      margin-right: 10px;
      border-radius:50px;
      position: relative;
      top: -15px;
 }
  .shop-info {
      flex-direction: column;
      font-size:12px;
      line-height:5%;
      margin-top: 3%;
      position: relative;
 }
     .reviews{
         margin-top: 30px;
    }
     .r-label{
         margin-top:40px;
         font-weight: 500;
         font-size: 15px;
         color:#555;
         display: inline;
    }
     .all p{
         margin-left:35%;
         margin-top:20px;
         font-size: 12px;
         color: #666;
    }
     .stars .fa {
         color: gold;
         font-size: .7em;
         margin-right: 3px;
    }
     .stars{
         display:flex;
         margin-top:15px;
    }
     .stars p{
         margin-left:5px;
         margin-top:-4px;
         font-size:11px;
         color:#666;
    }
     .p-review {
         display: flex;
         align-items: center;
        
    }
     .review-pic img{
         width: 30px;
         height: 30px;
         margin-right: 10px;
         border-radius:50px;
         position: relative;
         top: -10px;
    }
     .review-info {
         flex-direction: column;
         font-size:12px;
         line-height:5%;
         margin-top: 2%;
         position: relative;
    }
     .review-text {
         margin-top: 10px;

    }
     .r-date{
         font-size: 11px;
    }
     .r-star .fa {
         color: gold;
         font-size: .8em;
         margin-right: 1px;
    }
     .c-review{
         width:85%;
         text-align: justify;
         margin-left: 13%;
         margin-top: 10px;
         font-size: 11px;
    }
    
     
     .p-filter{
         text-align: center;
         font-size: 15px;
         margin-top: 10px;
         color: #777;
    }
     .f-label{
         font-size: 14px;
         color:#555;
         font-weight: 400;
         margin-top: 25px;
         margin-bottom:20px;
    }
     .price input{
         text-align: center;
         border-radius: 30px;
         color: #666;
         font-size: 14px;
    }
     .m-price{
         border: 1px solid #65A5A5;
         padding:5px;
         width:45%;
    }
     .m-price:focus {
         border: 2px solid #65A5A5;
         outline: none;
    }
     .m-price + .m-price {
         margin-left: 25px;
        /* Adjust the value as needed */
    }
     .m-price::placeholder{
         text-align: center;
         font-size: 14px;
    }
     .r-btn{
         align-items: center;
         padding: 5px 9px;
         border-radius: 30px;
         border: 1px solid #65A5A5;
         background: #FFF;
         color: #666;
        ;
         margin: 4px;
         cursor: pointer;
         width: auto;
         font-size: 13px;
    }
     .r-btn:focus{
         outline:none;
         border:none;
         background-color: #65A5A5;
         color: white;
    }
     .ratings-btn{
         align-items: center;
         padding: 5px 9px;
         border-radius:30px;
         border: 1px solid #65A5A5;
         background: #FFF;
         color: #666;
         margin: 5px 6px;
         cursor: pointer;
         width: 15%;
         font-size: 13px;
    }
     .ratings-btn:focus{
         outline:none;
         border:none;
         background-color: #65A5A5;
         color: white;
    }
     .ratings-btn i{
         color: #ff7e95;
    }
     .f-apply {
         display: flex;
         justify-content: center;
         align-items: center;
         margin: auto;
         text-align: center;
    }
     .apply {
         background-color: #65a5a5;
         color: white;
         border: none;
         padding: 12px;
         min-width: 345px;
         width: auto;
         border-radius: 30px;
         margin-top: 30px;
         margin-bottom: 20px;
    }
     .apply:focus {
         border: none;
         outline: none;
    }
    
    .other1{
        color: #555;
		font-size: 13px;
		font-style: normal;
		font-weight: 500;
		line-height: normal;
		margin-left: 5.5%;
        margin-top: -40px;
        margin-bottom: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
   }
    .service-list {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      margin-top: 6%;
     xml_get_current_column_number
   }

   .service {
      width: calc(9%);
      margin-bottom: 0px;
      margin-top: 0px;
      border: 1px solid #ccc;
      border-radius: 10px;
      display: flex;
      flex-direction: column;
      text-align: left;
   }

   .service img {
      max-width: 193px;
      max-height:150px;
      flex-grow: 1;
   }

   .service .service-info {
      padding: 10px;
   }

   .service .service-name {
      font-weight: 800;
      margin-top: 20px;
      margin-bottom: 5px;
      font-size: 14px;
      color: #666;
   }
   .service .service-category {
      color: #666;
      margin-bottom: 5px;
      font-size: 11px;
   }

   .service .service-price {
      color: #ff7e95;
      margin-bottom: 5px;
      font-size: 14px;
   }

   .service .service-ratings {
      color: #acaaaa;
      font-size: 11px;
      margin-top: 3px;
      margin-left: 25px;
   }
   .service .p {
      display: flex;
   }
   .p-end {
      color: #bebebe;
      font-size: 12px;
      text-align: center;
      margin-top: 30px;
   }
   .no_feedback{
        margin-left: 24%;
		margin-top: 20px;
		font-size: 12px;
    }
    .product-list {
         display: flex;
         flex-wrap: wrap;
         justify-content: center;
      margin-bottom: 30%;

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
         width: 130px;
         height:150px;
         flex-grow: 1;
    }

     .product .product-name {
        font-size: 13px;
        color: #666;
        width:120px;
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
.other {
		color: #555;
		font-size: 13px;
		font-style: normal;
		font-weight: 500;
		line-height: normal;
		margin-left: 5.5%;
		margin-top: 20px;
		margin-bottom: 0;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
    .package-checkbox{
        margin-left:120px;
        margin-top: 10px;
        text-align: right;
        position: absolute;
        transform: translateY(-230%);
    }
   /* Media query to adjust alignment for smaller screens */
   @media (max-width: 768px) {
      .service {
         width: calc(50% - 15px); /* Two items in a row */
      }
      .service-list{
         gap: 10px;
      }

         .column1 {
             text-align: center;
            /* Center align column 1 content */
        }
    
  
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
            <hr class="nav-hr">
        </header>
        <main class="main">
            <div class="container">
                <div class="column1">
                    <div class="image-container">
                        <div id="myCarousel" class="carousel slide" >
                    <?php if (isset($user) && isset($service)): ?>
                            <div class="carousel-inner">
                                <div class="carousel-item active" data-target="image1">
                                <?php
                                    echo '<img src="' . $user['profile_img'] . '" alt="' . $user['last_name'] . '">';
                                ?>                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column2">
                         <!-- Service and Shop Owner Details -->
                        <p class="p-name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
                      
           
                        <!-- Buttons and Actions -->
                        <div class="btn-container">
                            <div class="add-btn">
                                <a href="javascript:void(0);" onclick="addRequest()">
                                    <button class="add"><i class="bi bi-person-plus"></i>&nbsp;&nbsp;&nbsp;Add Request</button>
                                </a>

                                <a href="../chat.php?user_id=<?php echo htmlspecialchars($user['user_id']); ?>">
                                    <button class="messenger"><i class="bi bi-messenger"></i></button>
                                </a>
                            </div>
                        </div>
                
                        <p class="p-desc-label">Description</p>
                        <p class="p-desc"><?php echo htmlspecialchars($service['service_description']); ?></p>   
                    <?php endif; ?>

                    <div class="shop">
                        <div class="shop-pic">
                            <img src="<?php echo $shop['shop_img']; ?>" alt="Shop Profile">
                        </div>
                        <div class="shop-info">
                            <div class="info">
                                <p class="s-name"><?php echo $shop['shop_name']; ?></p>
                                <p class="s-location"><i class="bi bi-geo-alt"></i> <?php echo $shop['shop_address']; ?></p>
                                <a href="arranger_shop.php?shop_id=<?php echo $shop['shop_id']; ?>">
                                    <button class="view-shop"><i class="bi bi-shop-window"></i>View Shop</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
					// Ensure the function is defined outside of any conditional logic
					function get_average_rating($service_id) {
						$conn = dbconnect();
						$sql = "SELECT AVG(rating) as average_rating, COUNT(rating) as total_ratings
								FROM servicedetails 
								WHERE service_id = ? AND rating IS NOT NULL";

						try {
							$stmt = $conn->prepare($sql);
							$stmt->execute([$service_id]);
							$result = $stmt->fetch(PDO::FETCH_ASSOC);
							$conn = null;
							return $result;
						} catch (PDOException $e) {
							echo $sql . "<br>" . $e->getMessage();
							$conn = null;
							return false;
						}
					}

					// Check if product_id is set in the URL query string
					if (isset($_GET['service_id'])) {
						$service_id = $_GET['service_id'];
						$total = get_average_rating($service_id);
					}

					?>

                    <div class="reviews">
                        <p class="r-label">Service Ratings</p>
                    </div>
					<div class="stars">
						<?php if ($total) {
							$averageRating = round($total['average_rating']); // Round the average rating
							$averageRatingInt = (int)$averageRating; // Convert to integer to remove decimal part

							for ($i = 1; $i <= 5; $i++) {
								if ($i <= $averageRating) {
									echo '<i class="fa fa-star" aria-hidden="true"></i>';
								} else {
									echo '<i class="fa fa-star-o" aria-hidden="true"></i>';
								}
							}
							echo '<p class="r_stars">' . $averageRatingInt . ' & ' . $total['total_ratings'] . ' Reviews&nbsp;</p>';
						} ?>
					</div>
                    <?php
                    function get_service_details($service_id) {
                        $conn = dbconnect();
                        $sql = "SELECT * FROM services WHERE service_id = ?";
                        try {
                            $stmt = $conn->prepare($sql);
                            $stmt->execute([$service_id]);
                            $product_details = $stmt->fetch(PDO::FETCH_ASSOC);
                            $conn = null;
                            return $product_details;
                        } catch (PDOException $e) {
                            echo $sql . "<br>" . $e->getMessage();
                            $conn = null;
                            return false;
                        }
                    }
                        
                    if (isset($_GET['service_id'])) {
                        $service_id = $_GET['service_id'];
                    
                        // Fetch product details from the product table
                        $service_details = get_service_details($service_id);
                    
                        // Display the product details
                        if ($service_details) {
                            // ... (your existing code for displaying product details)
                    
                            // Display feedback and ratings
                            $feedbackAndRatings = get_feedback_and_ratings($service_id);
                    
                            if ($feedbackAndRatings) {
                                foreach ($feedbackAndRatings as $feedback) {
                                    // Fetch customer details
                                    $customer = get_customer_details($feedback['customer_id']);
                                    $fullName = $customer['first_name'] . ' ' . $customer['last_name'];
                                    
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
                            function get_feedback_and_ratings($service_id) {
                                $conn = dbconnect();
                                $sql = "SELECT * FROM servicedetails WHERE service_id = ?";
                                try {
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute([$service_id]);
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
                    <a href="see_all_reviews_service.php?service_id=<?php echo $service_id?>" class="all">
                        <p>See All Reviews</p>
                    </a>
                </div>
            </div>
             <section>               
                    <?php
                    $serviceID = $_GET['service_id'];
                    $service = get_service_details($serviceID);

                    // Assuming $arrangerID contains the arranger ID from the service details
                    $arrangerID = $service['arranger_id'];

                    // Display all packages of the same arranger
                    $packagesSameArranger = get_same_arranger_packages($arrangerID);

                    function get_same_arranger_packages($arrangerID) {
                        $conn = dbconnect();
                        $sql = "SELECT sp.* 
                                FROM service_package sp
                                JOIN services s ON sp.service_id = s.service_id
                                WHERE s.arranger_id = ? 
                                LIMIT 30";

                        try {
                            $stmt = $conn->prepare($sql);
                            $stmt->execute([$arrangerID]);
                            $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $conn = null;
                            return $packages;
                        } catch (PDOException $e) {
                            echo $sql . "<br>" . $e->getMessage();
                            $conn = null;
                            return false;
                        }
                    }
                    ?>

                    <!-- Display packages from the same arranger -->
                    <p class="other">Packages</p>
                    <div class="product-list" id="product-container" style="margin-bottom:40px">
                        <?php foreach ($packagesSameArranger as $package): ?>
                            <div class="product">
                                <!-- Checkbox added at the top right -->
                                <a href="customer_package.php?package_id=<?php echo $package['package_id']; ?>">
                                    <?php echo '<img src="' . $package['package_image'] . '" alt="' . $package['package_name'] . '">'; ?>
                                    <div class="product-name"><?php echo $package['package_name']; ?></div>
                                    <div class="product-price"><?php echo '₱ ' . $package['package_price']; ?></div>
                                    <input type="checkbox" class="package-checkbox" name="package_ids[]" value="<?php echo $package['package_id']; ?>" data-name="<?php echo $package['package_name']; ?>" data-price="<?php echo $package['package_price']; ?>" data-img="<?php echo $package['package_image']; ?>">
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>

                 


        
                         <!-- Assuming $serviceID contains the current service ID -->
                         <?php
                $serviceID = $_GET['service_id'];
                $service = get_service_details($serviceID);

                // Assuming $shopId contains the shop ID from the service details
                $shopId = $service['arranger_id'];

                // Display all products of the same shop (except Flower Bouquets)
                $otherProductsSameShop = get_other_products($shopId);

                // Display all products from different shops (excluding the same shop)
                $otherProductsDifferentShop = get_other_products($shopId);
                function get_other_products($shopId) {
                    $conn = dbconnect();
                
                    $sql = "SELECT p.*
                            FROM products p
                            JOIN shops s ON p.shop_owner = s.shop_id
                            WHERE s.shop_id != ? AND p.product_category != 'Flower Bouquets' AND p.product_status = 'Available'
                            LIMIT 30"; // Limiting to 30 products as in your original code
                
                    try {
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([$shopId]);
                        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $conn = null;
                        return $products;
                    } catch (PDOException $e) {
                        echo $sql . "<br>" . $e->getMessage();
                        $conn = null;
                        return false;
                    }
                }
                
                ?>

                <!-- Display products from the same shop (except Flower Bouquets) -->
                <p class="other">Same Shop Products</p>
                <div class="product-list" id="product-container">
                <?php
                
                foreach ($otherProductsSameShop as $product):
                    
                    
                    $counter = 0;
                    // Only display products with status 'Available'
                    if ($counter >= 30 || $product['product_status'] !== 'Available') {
                        continue; // Skip to the next iteration if the product status is not 'Available'
                    }

                    // Check if the product category is not "Flower Bouquet"
                    if ($product['product_category'] !== 'Flower Bouquets') {
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
                    }
                endforeach;
                ?>
                </div>

                <!-- Display products from different shops -->
                <p class="other1">Other Shop Products</p>
                <div class="product-list" id="product-container">
                <?php
         
                 $serviceID = $_GET['service_id'];
                 $service = get_service_details($serviceID);
 
                 // Assuming $shopId contains the shop ID from the service details
                 $shopId = $service['arranger_id'];
 
                 // Display all products of the same shop (except Flower Bouquets)
                 $otherProductsSameShop = get_service_products($shopId);
 
                 // Display all products from different shops (excluding the same shop)
                 $otherProductsDifferentShop = get_service_products($shopId);
                 function get_service_products($shopId) {
                     $conn = dbconnect();
                 
                     $sql = "SELECT p.*
                             FROM products p
                             JOIN shops s ON p.shop_owner = s.shop_id
                             WHERE s.shop_id != ? AND p.product_category != 'Flower Bouquets' AND p.product_status = 'Available'
                             LIMIT 30"; // Limiting to 30 products as in your original code
                 
                     try {
                         $stmt = $conn->prepare($sql);
                         $stmt->execute([$shopId]);
                         $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                         $conn = null;
                         return $products;
                     } catch (PDOException $e) {
                         echo $sql . "<br>" . $e->getMessage();
                         $conn = null;
                         return false;
                     }
                 }
                 
                 
                    foreach ($otherProductsSameShop as $product):
                        $counter = 0;
                        // Only display products with status 'Available'
                        if ($counter >= 30 || $product['product_status'] !== 'Available') {
                            continue; // Skip to the next iteration if the product status is not 'Available'
                        }

                        // Check if the product category is not "Flower Bouquet"
                        if ($product['product_category'] !== 'Flower Bouquets') {
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
                        }
                    endforeach;
                    ?>
                </div>
            
        
                <section>       
        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script> 
            document.addEventListener('DOMContentLoaded', function () {
            const carousel = document.getElementById('myCarousel');
            const imageLinks = document.querySelectorAll('.image-grid img');
            const indicators = document.querySelectorAll('.carousel-indicators li');
            
            imageLinks.forEach(function (imageLink, index) {
             imageLink.addEventListener('click', function () {
               const target = this.getAttribute('data-target');
            
               const carouselItems = document.querySelectorAll('.carousel-item');
               carouselItems.forEach(function (item) {
                 item.classList.remove('active');
                 if (item.getAttribute('data-target') === target) {
                   item.classList.add('active');
                 }
               });
            
               // Update the slide indicator
               indicators.forEach(function (indicator, indicatorIndex) {
                 if (index === indicatorIndex) {
                   indicator.classList.add('active');
                 } else {
                   indicator.classList.remove('active');
                 }
               });
            
               // Add border shadow to the clicked image
               imageLinks.forEach(function (img) {
                 img.style.border = 'none';
               });
               this.style.border = '2px solid #65a5a5';
            
               // Update the border when the carousel is navigated
               carousel.addEventListener('slide.bs.carousel', function () {
                 // Remove the border from all images
                 imageLinks.forEach(function (img) {
                   img.style.border = 'none';
                 });
            
                 // Add the border to the active image
                 const activeImage = carousel.querySelector('.active img');
                 activeImage.style.border = '2px solid #65a5a5';
               });
             });
            });
            });
            
        </script> 
        <script>
            function addRequest() {
                // Array to store selected packages
                var selectedPackages = [];

                // Get all checkboxes with class package-checkbox
                var checkboxes = document.querySelectorAll('.package-checkbox:checked');

                // Loop through the checkboxes and add selected packages to the array
                checkboxes.forEach(function (checkbox) {
                    var packageName = checkbox.getAttribute('data-name');
                    var packagePrice = checkbox.getAttribute('data-price');
                    var packageImg = checkbox.getAttribute('data-img');

                    selectedPackages.push({
                        id: checkbox.value,
                        name: packageName,
                        price: packagePrice,
                        img: packageImg
                    });
                });

                // Convert the array to JSON and encode it
                var selectedPackagesJSON = encodeURIComponent(JSON.stringify(selectedPackages));

                // Redirect to service_request.php with the selected packages data
                window.location.href = 'service_request.php?service_id=<?php echo $serviceID; ?>&packages=' + selectedPackagesJSON;
            }
        </script>
        <script>
            function goBack() {
                window.history.back();
            }
          </script>
    </body>
</html>
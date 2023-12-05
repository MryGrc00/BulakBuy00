<?php
session_start();
include '../php/dbhelper.php';
include '../php/checksession.php';

if (isset($_SESSION["user_id"])) {
    $seller_id = $_SESSION["user_id"];
    $products = get_latest_products_by_id('products','shops','subscription');
    $services = get_latest_services('services','users','shops','subscription');

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
.nav-item {
    list-style-type: none; /* This will remove the bullet point */
}

.nav-item form button {
    background: none; 
    border: none; 
    cursor: pointer;
    padding: 0; 
    font-size: inherit; 
    color: inherit; 
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
 .num-label{
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
 .home {
     background-color: #65a5a5;
     width: 60%;
     margin: auto;
     padding: 5px;
     border-radius: 8px;
     margin-top: -20px;
}
 .home li a{
     color:white;
     font-size: 14px;
}
 .icon-list {
     list-style: none;
     padding: 0;
     display: flex;
     justify-content: center;
     align-items: flex-end;
     margin-top: 10px;
     margin-bottom: 10px;
     color: white;
}
 .icon-list li {
     text-align: center;
     margin-left: 70px;
     position: relative;
}
 .icon-list i {
     font-size: 24px;
}
 .h-label {
     font-size: 14px;
     display: block;
     position: relative;
}
 .number {
     background-color: #ff7e95;
     border-radius: 50px;
     width: 15px;
     height: 15px;
     font-size: 10px;
     margin-top: -10px;
     margin-left: 30px;
     position: absolute;
     top: 5px;
     left: 30%;
     transform: translateX(-50%);
}
 .num-cart {
     background-color: #ff7e95;
     border-radius: 50px;
     width: 15px;
     height: 15px;
     font-size: 10px;
     margin-top: -10px;
     margin-left: 30px;
     position: absolute;
     top: 5px;
     left: 20%;
     transform: translateX(-50%);
}
 .container {
     max-width: 1170px;
     margin: 0 auto;
}
 .carousel {
     background-color: #f5f5f5;
     margin-top: 3%;
}
 .carousel-control-prev-icon, .carousel-control-next-icon {
     color: #000000;
     margin-top: 10%;
}
 .carousel-item {
     height: 32rem;
}
 .carousel-item img {
     width: 100%;
     height: 100%;
}
 
/* categories */
 .cat-label {
     color: #666;
     font-size: 17px;
     font-style: normal;
     font-weight: 500;
     line-height: normal;
     margin-left: 20%;
     margin-top: 40px;
     display: flex;
     justify-content: space-between;
     align-items: center;
     color:#555;
}
 .category-list {
     display: flex;
     justify-content: center;
     align-items: flex-start;
     background-color: #ffffff;
     padding: 1px;
}
 .category {
     text-align: center;
     width: calc(5.85% - 1px);
     margin: 18px;
     box-sizing: border-box;
     display: flex;
     flex-direction: column;
     align-items: center;
}
 .category img {
     width: 120px;
     height: 100px;
     display: block;
     border-radius: 5%;
}
 .category p {
     margin-top: 10px;
     font-size: 15px;
}
 .label {
     color: #666;
     font-size: 17px;
     font-style: normal;
     font-weight: 500;
     line-height: normal;
     margin-left: 20%;
     margin-top: 8px;
     margin-bottom: 30px;
     display: flex;
     justify-content: space-between;
     align-items: center;
}
 .all {
     float: right;
     margin-right: 26%;
     font-size: 15px;
     color: #666;
     text-decoration: none;
}
 .all:hover {
     color: lightgray;
     text-decoration: none;
}
 .label i {
     font-size: 18px;
     margin-left: 5px;
}
 .p-label {
     color: #666;
     font-size: 19px;
     font-style: normal;
     font-weight: 500;
     line-height: normal;
     margin-left: 20%;
     margin-top: 45px;
     margin-bottom: 30px;
     display: flex;
     justify-content: space-between;
     align-items: center;
}
 .p-all {
     float: right;
     margin-right: 27%;
     font-size: 15px;
     color: #666;
     text-decoration: none;
}
 .p-all:hover {
     color: lightgray;
     text-decoration: none;
}
 .p-label i {
     font-size: 18px;
     margin-left: 5px;
}
.product-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    gap: 20px;
    max-width: 1140px;
    margin: 0 auto;
}

.product {
    flex: 0 0 calc(4%);
    margin-top: -20px;
    margin-bottom: 20px;
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
     font-size: 14px;
     text-align: center;
     margin-top: 30px;
}
 .pc-label {
     color: #666;
     font-size: 19px;
     font-style: normal;
     font-weight: 500;
     line-height: normal;
     margin-left: 20%;
     margin-top: 50px;
     margin-bottom: 30px;
     display: flex;
     justify-content: space-between;
     align-items: center;
}
 .pc-all {
     float: right;
     margin-top: -15px;
     margin-right: 27%;
     font-size: 15px;
     color: #666;
     text-decoration: none;
}
 .pc-all:hover {
     color: lightgray;
     text-decoration: none;
}
 .pc-label i {
     font-size: 18px;
     margin-left: 5px;
}
 .dropdown {
     position: relative;
     display: inline-block;
     margin-left: 0;
}
 .dropbtn {
     border: 1px solid #65a5a5;
     background: transparent;
     color: white;
     margin: 0 2px;
     width: auto;
     font-size: 11px;
     cursor: pointer;
     position: relative;
    /* Add relative positioning */
}
 .dropbtn:focus{
     outline:none;
     border:none;
}
 .dropdown-content {
     display: none;
     position: absolute;
     align-items: center;
     background: rgb(255, 254, 254);
     color: #666;
     border-radius: 5px;
     min-width: 450px;
     z-index: 1;
     padding-right: 17px;
     top: 60px;
     left: 50%;
     max-height: 500px;
     overflow-y: auto;
     transform: translateX(-50%);
}
 .dropdown-content::-webkit-scrollbar {
     width: 8px;
     height:10px;
}
 .dropdown-content::-webkit-scrollbar-thumb {
     background: #f0f0f0;
     border-radius: 10px;
}
 .dropdown-content::-webkit-scrollbar-thumb:hover {
     background: #e4e4e4;
}
 .dropdown-content::before {
     content: "";
     position: absolute;
     top: -10px;
     left: 50%;
     transform: translateX(-50%);
     border-width: 0 10px 10px 10px;
     border-style: solid;
     border-color: transparent transparent white transparent;
}
 .notification-details {
     display: flex;
}
 .notification-details img {
     max-width: 80px;
     max-height: 90px;
     margin-right: 0px;
     margin-top: 20px;
     margin-left: 15px;
}
 .text-content {
     display: flex;
     flex-direction: column;
     align-items: flex-start;
     margin-top: 5px;
     font-size: 14px;
     color:#666;
     flex: 1;
     padding-left: 10px;
}
 .order-status, .order-description, .o-date-time {
     margin-bottom: 10px;
}
 .order-status{
     color:#222;
     margin-top: 10px;
}
 .order-description{
     margin-left: -2px;
     font-size: 13px;
     text-align: justify;
}
 .o-date-time{
     font-size: 12px;
}
 .order-date{
     margin-right:19px;
}
 .notif-hr{
     width:100%;
     margin: auto;
}
 @media (max-width: 768px) {
    .navbar{
        background-color: white;
         width:100%;
         z-index: 100;
    }
     .navbar img {
         width: 120px;
         height: 55px;
         margin: 0;
         margin-top:-5px;
    }
     .form {
         text-align: left;
         margin-top: 1px;
    }
     .form-input[type="text"] {
         height: 45px;
         width: 100%;
         background-color: #f0f0f0;
         border-radius: 10px;
         margin: 0;
         margin-top: -10px;
         font-size: 13px;
    }
     .form-inline .fa-search {
         font-size: 20px;
         margin-top: 35px;
         margin-left: 39px;
    }
     ::placeholder {
         font-size: 14px;
    }
     .icon-list {
         flex-direction: column;
         align-items: center;
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
     .num-label{
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
     .home {
         background-color: white;
         width: 105%;
         margin: auto;
         height: 70px;
         top: 91.5%;
         position: fixed;
         z-index: 100;
         border-radius: 0px;
    }
    .number{
        color: white;
    }
     .icon-list {
         list-style: none;
         padding: 0;
         display: flex;
         justify-content: center;
         margin-top: 1px;
         margin-left: -18px;
         margin-bottom: 10px;
         color: #65a5a5;
         
       
    }
     .icon-list li a {
         text-align: center;
         color: #65a5a5;
    }
     .icon-list i {
         font-size: 20px;
         
         margin: 0px 25px;
    }
     .h-label {
         font-size: 10px;
         display: block;
    }
     .container {
         max-width: 100%;
         margin: auto;
         margin-left: 0;
    }
     .carousel {
         background-color: #f5f5f5;
    }
     .carousel-control-prev-icon, .carousel-control-next-icon {
         color: #000000;
         margin-top: 10%;
    }
     .carousel-item {
         height: 10rem;
    }
     .carousel-item img {
         width: 350px;
         height: 160px;
    }
     .button-container {
         display: flex;
         justify-content: center;
         margin-top: 18px;
         margin-left: -10px;
    }
     .button {
         padding: 5px;
         border-radius: 19px;
         border: 1px solid #65a5a5;
         background: #fff;
         color: #666;
         margin: 0 25px;
         width: 80px;
         font-size: 11px;
    }
     .button:focus{
         outline:none;
         background-color: #65a5a5;
         color:white;
    }
     .cat-label {
         color: #666;
         font-size: 14px;
         font-style: normal;
         font-weight: 500;
         line-height: normal;
         margin-left: 3%;
         margin-top: 20px;
         display: flex;
         justify-content: space-between;
         align-items: center;
    }
     .category-list {
         display: flex;
         flex-wrap: wrap;
        /* Allow items to wrap to the next line */
         background-color: #ffffff;
         padding: 1px;
    }
     .category {
         text-align: center;
         margin: 0 10px;
         box-sizing: border-box;
         display: flex;
         flex-direction: column;
         align-items: center;
    }
     .category img {
         width: 80px;
         height: 70px;
         display: block;
         border-radius: 5%;
    }
     .category p {
         margin-top: 10px;
         font-size: 10px;
    }
     .label {
         color: #666;
         font-size: 14px;
         font-style: normal;
         font-weight: 500;
         line-height: normal;
         margin-left: 3%;
         margin-top: 10px;
         margin-bottom: 20px;
         display: flex;
         justify-content: space-between;
         align-items: center;
    }
     .all {
         float: right;
         margin-right: 5%;
         margin-top: -12px;
         font-size: 12px;
         color: #666;
         text-decoration: none;
    }
     .label i {
         font-size: 13px;
         margin-left: 5px;
    }
     .p-label {
         color: #666;
         font-size: 14px;
         font-style: normal;
         font-weight: 500;
         line-height: normal;
         margin-left: 3%;
         margin-top: 10px;
         margin-bottom: 20px;
         display: flex;
         justify-content: space-between;
         align-items: center;
    }
     .p-all {
         float: right;
         margin-right: 5%;
         margin-top: -12px;
         font-size: 12px;
         color: #666;
         text-decoration: none;
    }
     .p-label i {
         font-size: 13px;
         margin-left: 5px;
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
     .pc-label {
         color: #666;
         font-size: 14px;
         font-style: normal;
         font-weight: 500;
         line-height: normal;
         margin-left: 3%;
         margin-top: 10px;
         margin-bottom: 20px;
         display: flex;
         justify-content: space-between;
         align-items: center;
    }
     .pc-all {
         float: right;
         margin-right: 5%;
         margin-top: -12px;
         font-size: 12px;
         color: #666;
         text-decoration: none;
    }
     .pc-label i {
         font-size: 13px;
         margin-left: 5px;
    }
     .dropdown-content{
         display:none;
    }
    /* Media query to adjust alignment for smaller screens */
     @media (max-width: 768px) {
         .icon-list {
             flex-direction: row;
             align-items: center;
        }
         .icon-list li {
             margin: 12px;
        }
         .category {
             width: calc(19% - 1px);
            /* Two items in a row */
        }
         .product-list{
             gap: 10px;
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
                    <ul class="navbar-nav">
                        <a href="cart.php">
                            <li class="cart">
                                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                <p class="num-label">1 </p>
                            </li>
                        </a>
                        <li class="nav-item">
                            <form action="search_results.php" method="GET" class="form-inline my-2 my-lg-0">
                                <button type="submit"><i class="fa fa-search"></i></button>
                                <input type="text" name="search" id="search-input" class="form-control form-input" placeholder="Search">
                            </form>
                        </li>

                    </ul>
                </div>
            </nav>
        </header>
        <main>
            <div class="home text-center">
                <ul class="icon-list">
                    <li>                      
                        <a href="customer_home.php"><i class="fa fa-home" aria-hidden="true"></i></a>
                        <span class="h-label">Home</span>
                    </li>
                    <li>
                    <a href="../users.php"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
                        <p class="number">1</p>
                        <span class="h-label">Messages</span>
                    </li>
                    <li>
                        <a href="maps.php"><i class="fa fa-map-marker" aria-hidden="true"></i></a>
                        <span class="h-label">Maps</span>
                    </li>
                    
                    <li>
                        <a href="customer_profile.php"><i class="fa fa-user-o" aria-hidden="true"></i></a>
                        <p class="number">1</p>
                        <span class="h-label">Account</span>
                    </li>
                </ul>
            </div>
            <section>
                <div class="container">
                    <div id="myCarousel" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                            <li data-target="#myCarousel" data-slide-to="1"></li>
                            <li data-target="#myCarousel" data-slide-to="2"></li>
                            <li data-target="#myCarousel" data-slide-to="3"></li>
                        </ol>
                        <!-- Slides -->
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="https://i.pinimg.com/originals/8f/82/33/8f8233d80203bf7c0ad423121051f28a.jpg" alt="Image 1">
                            </div>
                            <div class="carousel-item">
                                <img src="https://t3.ftcdn.net/jpg/05/47/05/60/360_F_547056070_qrtAaQNjUzKmRoo33HztmrIApHT70Zh4.jpg" alt="Image 2">
                            </div>
                            <div class="carousel-item">
                                <img src="https://www.shandonflowers.com/upload/mt/shan533/upload/files/images/panels/15-florist-choice.jpg" alt="Image 3">
                            </div>
                            <div class="carousel-item">
                                <img src="https://www.flowershopnetwork.com/blog/wp-content/uploads/2019/12/ChristmasGifts.png" alt="Image 4">
                            </div>
                        </div>
                        <!-- Left and right controls -->
                        <a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        </a>
                        <a class="carousel-control-next" href="#myCarousel" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        </a>
                    </div>
                </div>
            </section>
            <section>
                <div class="cat-label">
                    <p>Categories</p>
                </div>
                <div class="category-list">
                    
                    <div class="category">
                        <a href="category.php?category=Flower Bouquets" ><img src="https://assets.florista.ph/uploads/product-pics/5022_86_5022.webp" alt="Category 3"></a>
                        <p>Flower Bouquets</p>
                    </div>
                    <div class="category">
                        <a href="category.php?category=Candles" ><img src="https://www.soapcraftz.com/wp-content/uploads/2021/11/candle-additives.webp" alt="Category 1"></a>
                        <p>Candles</p>
                    </div>
                    <div class="category">
                        <a href="category.php?category=Tropical Flowers" ><img src="https://5.imimg.com/data5/SELLER/Default/2023/5/311880205/FY/ED/MT/181342412/torch-ginger-red-500x500.jpg" alt="Category 2"></a>
                        <p>Tropical Flowers</p>
                    </div>
                    <div class="category">
                        <a href="category.php?category=Flower Bundles" ><img src="https://gumlet.assettype.com/sunstar%2Fimport%2Fuploads%2Fimages%2F2019%2F10%2F29%2F187066.jpg?format=auto" alt="Category 2"></a>
                        <p>Flower Bundles</p>
                    </div>
                    <div class="category">
                        <a href="category.php?category=Arrangement Materials" ><img src="https://flowermoxiesupply.com/cdn/shop/products/50373055808_8a22415eb2_c.jpg?v=1626454724" alt="Category 3"></a>
                        <p>Arrangement Materials</p>
                    </div>
                    <div class="category">
                        <a href="category.php?category=Flower Stands" ><img src="https://img2.chinadaily.com.cn/images/202112/17/61bc1548a310cdd3d82174b3.jpeg" alt="Category 1"></a>
                        <p>Flower Stands</p>
                    </div>
                    <div class="category">
                        <a href="category.php?category=Leaves" ><img src="https://casajuan.ph/cdn/shop/products/anahawnapkinring.jpg?v=1626910031" alt="Category 3"></a>
                        <p>Leaves</p>
                    </div>
                    <div class="category">
                        <a href="category.php?category=Other" ><img src="https://i.ebayimg.com/images/g/dbcAAOSwQL5iJtd2/s-l1200.webp" alt="Category 3"></a>
                        <p>Other</p>
                    </div>
                </div>
            </section>
            <section>
                <div class="label">
                    <p>Products</p>
                    <a href="allproducts.php" class="all">See all<i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
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

            <section>
                <div class="label">
                    <p>Services</p>
                    <a href="allservices.php" class="all">See all<i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                </div>
                <div class="product-list" id="product-container">
                <?php 
                    $counter = 0;
                    foreach ($services as $service):
                        if ($counter >= 6) {
                            break; // Stop the loop after displaying 6 products
                        }
                        ?>                        
                        <div class="product">
                            <a href="customer_service.php?service_id=<?php echo $service['service_id']; ?>">
                                <?php
                                echo '<img src="' . $service['profile_img'] . '" alt="' . $service['last_name'] . '">';
                                ?>
                                <div class="product-name"><?php echo $service['first_name'] . ' ' . $service['last_name']; ?></div>
                                <div class="p">
                                    <div class="product-price"><?php echo '₱ '. $service['service_rate']; ?></div>
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>
            // JavaScript to handle responsive behavior
            const dropdownContent = document.querySelector(".dropdown-content");
            const notificationIcon = document.querySelector(".dropbtn a");
            const dropdownBtn = document.getElementById("dropdownBtn");
            
            // Check the screen width
            function checkScreenWidth() {
                if (window.innerWidth <= 768) { // Adjust the breakpoint as needed
                    dropdownContent.style.display = "none"; // Hide dropdown on small screens
                    dropdownBtn.addEventListener("click", redirectToNotification);
                } else {
                    dropdownContent.style.display = "none"; // Hide dropdown on larger screens
                    dropdownBtn.removeEventListener("click", redirectToNotification);
                }
            }
            
            // Redirect to the notification page
            function redirectToNotification(event) {
                event.preventDefault();
                window.location.href = "../common/notification.html";
            }
            
            // Toggle dropdown when the notification icon is clicked
            dropdownBtn.addEventListener("click", function() {
                if (dropdownContent.style.display === "none" || dropdownContent.style.display === "") {
                    dropdownContent.style.display = "block";
                } else {
                    dropdownContent.style.display = "none";
                }
            });
            
            // Initial check
            checkScreenWidth();
            
            // Listen for window resize
            window.addEventListener("resize", checkScreenWidth);
        </script>
    </body>
</html>
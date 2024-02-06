
<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order Delivered</title>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../css/request_status.css">
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
									<div id="search-results">Request Status</div>
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
					<div class="cart-container">
                    <?php 

                        session_start();
                        include '../php/dbhelper.php';

                        if (isset($_SESSION['user_id']) &&  isset($_GET['servicedetails_id'])){
                            $user_id = $_SESSION['user_id'];
                            $servicedetails_id = $_GET['servicedetails_id'];

                            // Fetch product details from the product table
                            $details= getServiceDetails("servicedetails", "services", "users", $servicedetails_id, $user_id);


                        }
                        if (isset($details)) {
                            echo '<div class="all-items">';
                            echo '<h6 class="items-label">Request Details</h6>';
                            echo '</div>';
                            echo '<div class="cart-item">';
                            echo '<div class="custom-checkbox" style="margin-top:-30px">';
                            echo '<img src="' . $details["arranger_profile"] . '" alt="Service Image">';
                            echo '</div>';
                            echo '<div class="item-details">';
                            echo '<h2>' . $details["arranger_first_name"] . " " . $details["arranger_last_name"] . '</h2>';
                            echo '<div class="loc">';
                            echo '<p class="location">' . $details['arranger_address'] . '</p>';
                            echo '</div>';
                            echo '<div class="num">';
                            echo '<p class="number">' . $details['arranger_phone'] . '</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        } else {
                            echo "Service details not found.";
                        }
          ?>
                   <?php
                    if (isset($_SESSION['user_id']) && isset($_GET['servicedetails_id'])) {
                        $user_id = $_SESSION['user_id'];
                        $servicedetails_id = $_GET['servicedetails_id'];

                        // Fetch service details from the servicedetails table
                        $details1 = getService_Details("servicedetails", "services", "users", "service_package", $servicedetails_id, $user_id);
                    }

                    if (isset($details1)) {
                        echo '<div class="all-items">';
                        echo '<h6 class="items-label">Package Details</h6>';
                        echo '</div>';
                        echo '<div class="cart-item">';
                        echo '<div class="custom-checkbox" style="margin-top:-30px">';
                        echo '<img src="' . $details1["package_image"] . '" alt="Package Image">';
                        echo '</div>';
                        echo '<div class="item-details">';
                        echo '<h2>' . $details1["package_name"] . '</h2>';
                        echo '<div class="loc">';
                        echo '<p class="location">' . $details1['inclusions'] . '</p>'; // Assuming package_description is the field for inclusions
                        echo '</div>';
                        echo '<div class="num">';
                        echo '<p class="number">' . '₱ ' . $details1['package_price'] . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    } else {
                        echo "Service details not found.";
                    }

                    function getService_Details($servicedetailsTable, $servicesTable, $usersTable, $servicePackageTable, $servicedetails_id, $user_id)
                    {
                        $conn = dbconnect();

                        // SQL to join servicedetails with services, then with users and service_package
                        $sql = "SELECT sd.*, sp.package_image, sp.package_name, sp.inclusions, sp.package_price
                                FROM " . $servicedetailsTable . " AS sd
                                JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
                                JOIN " . $servicePackageTable . " AS sp ON sd.package_id = sp.package_id
                                WHERE sd.servicedetails_id = :servicedetails_id AND sd.customer_id = :user_id";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':servicedetails_id', $servicedetails_id, PDO::PARAM_INT);
                        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                        $stmt->execute();

                        return $stmt->fetch(PDO::FETCH_ASSOC);
                    }
                    ?>

                        <hr class="cart-hr">
						<div class="timeline">

                            <?php
                                                
                            // Check if product_id is set in the URL
                            if (isset($_GET['servicedetails_id'])) {
                                $servicedetails_id = $_GET['servicedetails_id'];

                                // Fetch status from the sales table
                                $status = get_service_status_by_servicedetails_id($servicedetails_id);

                            // Display the corresponding HTML based on the status
                            if ($status === 'Pending') {
                            echo '<div class="status">
                                    <div class="status-icon">1</div>
                                    <div class="status-info">
                                        <div class="status-text">Order Placed</div>
                                    </div>
                                
                                </div>';

                            } 
                            elseif ($status === 'Cancelled') {
                                echo '<div class="status">
                                        <div class="status-icon">1</div>
                                        <div class="status-info">
                                            <div class="status-text">Order Cancelled</div>
                                        </div>
                                       
                                    </div>
                                    
                                    
                                    ';
                                }
                                elseif ($status === 'Processing') {
                            echo '<div class="status">
                                    <div class="status-icon">1</div>
                                    <div class="status-info">
                                        <div class="status-text">Order Placed</div>
                                    </div>
                                    <div class="vertical-line"></div>
                                    <!-- Vertical line -->
                                </div>
                                <div class="status">
                                    <div class="status-icon">2</div>
                                    <div class="status-info">
                                        <div class="status-text">Processing</div>
                              </div>
                                
                                </div>';
                            } elseif ($status === 'Intransit') {
                            echo '<div class="status">
                                    <div class="status-icon">1</div>
                                    <div class="status-info">
                                        <div class="status-text">Order Placed</div>
                                    </div>
                                    <div class="vertical-line"></div>
                                    <!-- Vertical line -->
                                </div>
                                <div class="status">
                                    <div class="status-icon">2</div>
                                    <div class="status-info">
                                        <div class="status-text">Processing</div>
                                    </div>
                                    <div class="vertical-line"></div>
                                    <!-- Vertical line -->
                                </div>
                                <div class="status">
                                    <div class="status-icon">3</div>
                                    <div class="status-info">
                                        <div class="status-text">In Transit</div>
                                    </div>
                                
                                </div>';
                            } elseif ($status === 'Completed') {
                            echo '<div class="status">
                                    <div class="status-icon">1</div>
                                    <div class="status-info">
                                        <div class="status-text">Order Placed</div>
                                    </div>
                                    <div class="vertical-line"></div>
                                    <!-- Vertical line -->
                                </div>
                                <div class="status">
                                    <div class="status-icon">2</div>
                                    <div class="status-info">
                                        <div class="status-text">Processing</div>
                                    </div>
                                    <div class="vertical-line"></div>
                                    <!-- Vertical line -->
                                </div>
                                <div class="status">
                                    <div class="status-icon">3</div>
                                    <div class="status-info">
                                        <div class="status-text">In Transit</div>
                                    </div>
                                    <div class="vertical-line"></div>
                                    <!-- Vertical line -->
                                </div>
                                <div class="status">
                                    <div class="status-icon">4</div>
                                    <div class="status-info">
                                        <div class="status-text">Delivered</div>
                                    </div>
                                </div>';
                            } else {
                                    echo "Invalid status.";
                                    }
                            } else {
                            echo "Product ID not provided.";
                            }

                            function get_service_status_by_servicedetails_id($servicedetails_id) {
                                $conn = dbconnect();
                                $sql = "SELECT status FROM servicedetails WHERE servicedetails_id = ?";

                                try {
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute([$servicedetails_id]); // Use the function parameter here
                                    $status = $stmt->fetchColumn();
                                    $conn = null;
                                    return $status;
                                } catch (PDOException $e) {
                                    echo $sql . "<br>" . $e->getMessage();
                                    $conn = null;
                                    return false;
                                }
                            }


                            ?>


						</div>
						<div class="all-items">
							<h6 class="items-label">Customer Details</h6>
						</div>
						<div class="dev-details">
							<p><?php echo $details["customer_first_name"]. " " . $details["customer_last_name"]; ?></p>
							<p><?php echo $details["customer_address"]; ?></p>
							<p><?php echo $details["customer_phone"]; ?></p>
						</div>
					</div>
				</div>
				<div class="column2">
					<div class="summary-container">
						<div class="order-summary">
							<h6 class="order-label">Request Summary</h6>
						</div>
						<div class="summary-items">
							<div class="sub-total">
								<div class="sched-date">
									<p class="sched">Date</p>
									<p class="date"><?php echo $details["date"]; ?></p>
								</div>
                                <div class="sched-date">
									<p class="sched">Time</p>
									<p class="date"><?php echo $details["time"]; ?></p>
								</div>
								<div class="product-price">
									<p class="product">Service Price</p>
									<p class="order-price">₱<?php echo $details["amount"]; ?>	</p>
                                </div>
								</div>
                              
								<div class="total-payment">
									<p class="total">Total</p>
									<p class="t-payment">₱ <?php echo $details["amount"]; ?></p><br>
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
			function goBack() {
				window.history.back();
			}
		  </script>
    </body>
</html>
<?php
$hostname = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'bulakbuy';

function dbconnect() {
    global $hostname, $username, $password, $database;
    try {
        $conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}

function add_record($table, $fields, $data) {
    $okay = false;
    if (count($fields) == count($data)) {
        $attributes = implode(", ", $fields);
        $placeholders = implode(", ", array_fill(0, count($fields), '?'));
        $sql = "INSERT INTO $table ($attributes) VALUES ($placeholders)";
        $conn = dbconnect();
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($data);
            $okay = true;
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
        $conn = null;
    }
    return $okay;
}


function update_record($table, $fields, $data, $where, $id) {
    $okay = -1;
    $attributes = array();
    
    if(count($fields) == count($data)) {
        // Construct SET part of the SQL query
        for ($i = 0; $i < count($fields); $i++) { 
            // Use prepared statements to prevent SQL injection
            $attributes[] = $fields[$i] . " = :data" . $i;
        }
        
        // Implode the attributes array to create the SET part of the query
        $attri = implode(", ", $attributes);
        
        // Construct the SQL query
        $sql = "UPDATE $table SET $attri WHERE $where = :id";
        
        $conn = dbconnect();
        
        try {
            $stmt = $conn->prepare($sql);
            
            // Bind parameters using prepared statements
            for ($i = 0; $i < count($data); $i++) {
                $stmt->bindParam(":data" . $i, $data[$i]);
            }
            $stmt->bindParam(":id", $id);
            
            // Execute the statement
            $stmt->execute();
            
            // Get the number of affected rows
            $okay = $stmt->rowCount();
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
        
        $conn = null;
    }
    
    return $okay;
}


function delete_record($table, $where, $id) {
    $okay = -1;
    $sql = "DELETE FROM $table WHERE $where = '$id'";
    $conn = dbconnect();
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $okay = $stmt->rowCount();
    } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
    $conn = null;
    return $okay;
}


function all_record($table) {
    $rows = array();
    $sql = "SELECT * FROM $table";
    $conn = dbconnect();
    try {
        $stmt = $conn->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
    $conn = null;
    return $rows;
}

function get_record($table, $where, $id) {
    $row = null;
    $sql = "SELECT * FROM $table WHERE $where = '$id'";
    $conn = dbconnect();
    try {
        $stmt = $conn->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC); // Use fetch instead of fetchAll
    } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
    $conn = null;
    return $row; // Return a single record, not an array of records
}



function get_record_by_user($user_id) {
    $conn = dbconnect();
    
    // Prepare SQL query with a WHERE clause to filter by seller_id
    $sql = "SELECT * FROM  users WHERE user_id = :user_id";
    
    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
    
    // Bind the seller_id parameter
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    
    // Execute the query
    $stmt->execute();
    
    // Fetch all matching products as an associative array
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Close the database connection
    $conn = null;
    
    // Return the products
    return $users;
}

function check_login($redirect = true){

	if(isset($_SESSION['USER']) && isset($_SESSION['LOGGED_IN'])){

		return true;
	}

	if($redirect){
		header("Location: index.php");
		die;
	}else{
		return false;
	}
	
}

function generateUniqueFileName($originalFileName) {
    $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
    return uniqid() . '.' . $extension;
}


//before entering the vendor_home
    function is_shop_empty($userId) {
        global $pdo; // Ensure that $pdo is your PDO database connection variable

        // Check if the shop table has an entry for this user using a prepared statement
        $query = "SELECT COUNT(*) AS shop_count FROM shops WHERE owner_id = :userId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['shop_count'] == 0;
        }
        return true; // Return true if query fails, assuming no shop exists
    }

    // Function to format price with commas for thousands
    function formatPrice($price) {
        return '₱ ' . number_format($price);
    }

    function get_products_by_user($user_id, $pdo) {
        // Define the SQL query
        $sql = "SELECT p.* FROM products p
                INNER JOIN shops s ON p.shop_owner = s.shop_id
                INNER JOIN users u ON s.owner_id = u.user_id
                WHERE u.user_id = :user_id AND u.role IN ('seller', 'arranger')";
    
        // Prepare and execute the query
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    
        // Fetch the products and return them
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function get_services($servicesTable, $usersTable) {
        $conn = dbconnect(); 
        $sql = "SELECT s.*, u.first_name, u.last_name, u.profile_img FROM " . $servicesTable . " s 
                JOIN " . $usersTable . " u ON s.arranger_id = u.user_id";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    
        return $services;
    }
    

function get_latest_products_by_id($productsTable, $shopTable, $subscribedTable) {
    $conn = dbconnect(); 

    if ($conn) {
        try {
            // SQL to join products table with shop and subscribed tables
            // Exclude products from shops with status 'blocked'
            $sql = "SELECT p.* FROM " . $productsTable . " AS p
                    JOIN " . $shopTable . " AS s ON p.shop_owner = s.shop_id
                    LEFT JOIN " . $subscribedTable . " AS sub ON s.shop_id = sub.shop_id
                    WHERE s.status != 'blocked'
                    ORDER BY (sub.status = 'active') DESC, p.product_id DESC";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            // Handle SQL errors or connection problems
            echo "Database error: " . $e->getMessage();
            return [];
        }
    } else {
        // Handle the case where connection could not be established
        echo "Unable to connect to the database.";
        return [];
    }
}
    
    function get_latest_services($servicesTable, $usersTable, $shopTable, $subscribedTable) {
        $conn = dbconnect(); 
    
        if ($conn) {
            try {
                // SQL to join services table with users, shops, and subscriptions
                // Exclude services from shops with status 'blocked'
                $sql = "SELECT s.*, u.first_name, u.last_name, u.profile_img FROM " . $servicesTable . " AS s
                        JOIN " . $usersTable . " AS u ON s.arranger_id = u.user_id
                        LEFT JOIN " . $shopTable . " AS sh ON u.user_id = sh.owner_id
                        LEFT JOIN " . $subscribedTable . " AS sub ON sh.shop_id = sub.shop_id
                        WHERE s.status = 'enabled' AND sh.status != 'blocked'
                        ORDER BY (sub.status = 'active') DESC, s.service_id DESC";
                
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                
                return $stmt->fetchAll(PDO::FETCH_ASSOC); 
            } catch (PDOException $e) {
                // Handle SQL errors or connection problems
                echo "Database error: " . $e->getMessage();
                return [];
            }
        } else {
            // Handle the case where connection could not be established
            echo "Unable to connect to the database.";
            return [];
        }
    }
    
    
    
    
    
    
    function get_price_range_products() {
        $conn = dbconnect(); 
        $query = "SELECT MIN(product_price) AS min_price, MAX(product_price) AS max_price FROM products";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return array($row['min_price'], $row['max_price']);
    }

    function get_rate_range_service() {
        $conn = dbconnect(); 
        $query = "SELECT MIN(service_rate) AS min_price, MAX(service_rate) AS max_price FROM services";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return array($row['min_price'], $row['max_price']);
    }
    
    //service_order.php
    function get_pending_service_details($servicedetailsTable, $servicesTable, $usersTable, $loggedInUserId) {
        $conn = dbconnect();
    
        // SQL to join servicedetails with services, then with users to get the customer's name
        $sql = "SELECT sd.servicedetails_id, sd.*, u.first_name AS customer_first_name, u.last_name AS customer_last_name, u.address AS customer_address, u.profile_img AS customer_profile
                FROM " . $servicedetailsTable . " AS sd
                JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
                JOIN " . $usersTable . " AS u ON sd.customer_id = u.user_id
                WHERE s.arranger_id = :loggedInUserId AND sd.status = 'Pending'
                ORDER BY sd.servicedetails_id DESC"; // Including servicedetails_id in the selection
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
//count
    function get_count_of_pending_services($salesdetailsTable, $servicesTable, $loggedInUserId) {
        $conn = dbconnect();
    
        // SQL to count the number of Pending sales for services offered by the logged-in arranger
        $sql = "SELECT COUNT(*) as sales_count 
                FROM " . $salesdetailsTable . " AS sd
                JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
                WHERE s.arranger_id = :loggedInUserId AND sd.status = 'Pending'";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['sales_count'] : 0;
    }

    

//Processing
    function get_service_details_processing($servicedetailsTable, $servicesTable, $usersTable, $loggedInUserId) {
        $conn = dbconnect();
    
        // SQL to join servicedetails with services and users, filtering for 'Processing' status
        $sql = "SELECT sd.servicedetails_id, sd.*, u.first_name AS customer_first_name, u.last_name AS customer_last_name, 
                    u.address AS customer_address, u.profile_img AS customer_profile
                FROM " . $servicedetailsTable . " AS sd
                JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
                JOIN " . $usersTable . " AS u ON sd.customer_id = u.user_id
                WHERE s.arranger_id = :loggedInUserId AND sd.status = 'Processing'
                ORDER BY sd.servicedetails_id DESC"; 
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

//count of services
function get_count_of_processing_services($servicedetailsTable, $servicesTable, $loggedInUserId) {
    $conn = dbconnect();

    // SQL to count the number of service details in 'Processing' status for the logged-in user's services
    $sql = "SELECT COUNT(*) as count
            FROM " . $servicedetailsTable . " AS sd
            JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
            WHERE s.arranger_id = :loggedInUserId AND sd.status = 'Processing'";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['count'] : 0;
}



//Intransit
function get_service_details_intransit($servicedetailsTable, $servicesTable, $usersTable, $loggedInUserId) {
    $conn = dbconnect();

    // SQL to join servicedetails with services and users, filtering for 'Processing' status
    $sql = "SELECT sd.servicedetails_id,sd.*, u.first_name AS customer_first_name, u.last_name AS customer_last_name, 
                u.address AS customer_address, u.profile_img AS customer_profile
            FROM " . $servicedetailsTable . " AS sd
            JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
            JOIN " . $usersTable . " AS u ON sd.customer_id = u.user_id
            WHERE s.arranger_id = :loggedInUserId AND sd.status = 'Intransit'
            ORDER BY sd.servicedetails_id DESC"; 
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


//count
function get_count_of_intransit_services($servicedetailsTable, $servicesTable, $loggedInUserId) {
    $conn = dbconnect();

    // SQL to count the number of service details in 'Intransit' status for the logged-in user's services
    $sql = "SELECT COUNT(*) as count
            FROM " . $servicedetailsTable . " AS sd
            JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
            WHERE s.arranger_id = :loggedInUserId AND sd.status = 'Intransit'";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['count'] : 0;
}




//completed
function get_service_details_completed($servicedetailsTable, $servicesTable, $usersTable, $loggedInUserId) {
    $conn = dbconnect();

    $sql = "SELECT sd.servicedetails_id, sd.*, u.first_name AS customer_first_name, u.last_name AS customer_last_name , u.address AS customer_address, u.profile_img AS customer_profile
            FROM " . $servicedetailsTable . " AS sd
            JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
            JOIN " . $usersTable . " AS u ON sd.customer_id = u.user_id
            WHERE s.arranger_id = :loggedInUserId AND sd.status = 'Completed'
            ORDER BY sd.servicedetails_id DESC"; 
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//count of service
function get_count_of_completed_services($servicedetailsTable, $servicesTable, $loggedInUserId) {
    $conn = dbconnect();

    // SQL to count the number of service details in 'Intransit' status for the logged-in user's services
    $sql = "SELECT COUNT(*) as count
            FROM " . $servicedetailsTable . " AS sd
            JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
            WHERE s.arranger_id = :loggedInUserId AND sd.status = 'Completed'";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['count'] : 0;
}
//product count



//Pending for customer side
function get_pending_service_details_arranger($servicedetailsTable, $servicesTable, $usersTable, $loggedInUserId){
    $conn = dbconnect();
    
    // SQL to join servicedetails with services, then with users to get the arranger's name and details
    $sql = "SELECT  sd.servicedetails_id, sd.*, u.first_name AS arranger_first_name, u.last_name AS arranger_last_name, u.address AS arranger_address, u.profile_img AS arranger_profile
            FROM " . $servicedetailsTable . " AS sd
            JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
            JOIN " . $usersTable . " AS u ON s.arranger_id = u.user_id
            WHERE sd.customer_id = :loggedInUserId AND sd.status = 'Pending'
            ORDER BY sd.servicedetails_id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_cancelled_service_details_arranger($servicedetailsTable, $servicesTable, $usersTable, $loggedInUserId){
    $conn = dbconnect();
    
    // SQL to join servicedetails with services, then with users to get the arranger's name and details
    $sql = "SELECT sd.*, u.first_name AS arranger_first_name, u.last_name AS arranger_last_name, u.address AS arranger_address, u.profile_img AS arranger_profile
            FROM " . $servicedetailsTable . " AS sd
            JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
            JOIN " . $usersTable . " AS u ON s.arranger_id = u.user_id
            WHERE sd.customer_id = :loggedInUserId AND sd.status = 'cancelled'
            ORDER BY sd.servicedetails_id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


//count
function get_pending_service_details_count_arranger($servicedetailsTable, $servicesTable, $usersTable, $loggedInUserId) {
    $conn = dbconnect();
    
    // SQL to count the number of Pending servicedetails for an arranger
    $sql = "SELECT COUNT(*) AS pending_count
            FROM " . $servicedetailsTable . " AS sd
            JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
            WHERE sd.customer_id = :loggedInUserId AND sd.status = 'Pending'";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetching only one row as it's a COUNT query
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the count. If there's no record, return 0.
    return $result ? (int) $result['pending_count'] : 0;
}
//cancelled
function get_cancelled_service_details_count_arranger($servicedetailsTable, $servicesTable, $usersTable, $loggedInUserId) {
    $conn = dbconnect();
    
    // SQL to count the number of Pending servicedetails for an arranger
    $sql = "SELECT COUNT(*) AS pending_count
            FROM " . $servicedetailsTable . " AS sd
            JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
            WHERE sd.customer_id = :loggedInUserId AND sd.status = 'Cancelled'";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetching only one row as it's a COUNT query
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the count. If there's no record, return 0.
    return $result ? (int) $result['pending_count'] : 0;
}

//process for customer side

function get_process_service_details_arranger($servicedetailsTable, $servicesTable, $usersTable, $loggedInUserId){
    $conn = dbconnect();
    
    // SQL to join servicedetails with services, then with users to get the arranger's name and details
    $sql = "SELECT sd.*, u.first_name AS arranger_first_name, u.last_name AS arranger_last_name, u.address AS arranger_address, u.profile_img AS arranger_profile
            FROM " . $servicedetailsTable . " AS sd
            JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
            JOIN " . $usersTable . " AS u ON s.arranger_id = u.user_id
            WHERE sd.customer_id = :loggedInUserId AND sd.status = 'Processing'
            ORDER BY sd.servicedetails_id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//count
function get_process_service_details_count_arranger($servicedetailsTable, $servicesTable, $usersTable, $loggedInUserId) {
    $conn = dbconnect();
    
    // SQL to count the number of Pending servicedetails for an arranger
    $sql = "SELECT COUNT(*) AS pending_count
            FROM " . $servicedetailsTable . " AS sd
            JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
            WHERE sd.customer_id = :loggedInUserId AND sd.status = 'Processing'";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetching only one row as it's a COUNT query
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the count. If there's no record, return 0.
    return $result ? (int) $result['pending_count'] : 0;
}


//Intransit for customer side
function get_intransit_service_details_arranger($servicedetailsTable, $servicesTable, $usersTable, $loggedInUserId){
    $conn = dbconnect();
    
    // SQL to join servicedetails with services, then with users to get the arranger's name and details
    $sql = "SELECT sd.*, u.first_name AS arranger_first_name, u.last_name AS arranger_last_name, u.address AS arranger_address, u.profile_img AS arranger_profile
            FROM " . $servicedetailsTable . " AS sd
            JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
            JOIN " . $usersTable . " AS u ON s.arranger_id = u.user_id
            WHERE sd.customer_id = :loggedInUserId AND sd.status = 'Intransit'
            ORDER BY sd.servicedetails_id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//count
function get_intransit_service_details_count_arranger($servicedetailsTable, $servicesTable, $usersTable, $loggedInUserId) {
    $conn = dbconnect();
    
    // SQL to count the number of Pending servicedetails for an arranger
    $sql = "SELECT COUNT(*) AS pending_count
            FROM " . $servicedetailsTable . " AS sd
            JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
            WHERE sd.customer_id = :loggedInUserId AND sd.status = 'Intransit'";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetching only one row as it's a COUNT query
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the count. If there's no record, return 0.
    return $result ? (int) $result['pending_count'] : 0;
}

//completed for customer side
function get_completed_service_details_arranger($servicedetailsTable, $servicesTable, $usersTable, $loggedInUserId){
    $conn = dbconnect();
    
    // SQL to join servicedetails with services, then with users to get the arranger's name and details
    $sql = "SELECT sd.*, u.first_name AS arranger_first_name, u.last_name AS arranger_last_name, u.address AS arranger_address, u.profile_img AS arranger_profile
            FROM " . $servicedetailsTable . " AS sd
            JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
            JOIN " . $usersTable . " AS u ON s.arranger_id = u.user_id
            WHERE sd.customer_id = :loggedInUserId AND sd.status = 'completed'
            ORDER BY sd.servicedetails_id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//count
function get_completed_service_details_count_arranger($servicedetailsTable, $servicesTable, $usersTable, $loggedInUserId) {
    $conn = dbconnect();
    
    // SQL to count the number of Pending servicedetails for an arranger
    $sql = "SELECT COUNT(*) AS pending_count
            FROM " . $servicedetailsTable . " AS sd
            JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
            WHERE sd.customer_id = :loggedInUserId AND sd.status = 'completed'";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetching only one row as it's a COUNT query
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the count. If there's no record, return 0.
    return $result ? (int) $result['pending_count'] : 0;
}

//for order status.
function getServiceDetails($servicedetailsTable, $servicesTable, $usersTable, $servicedetailsId, $loggedInUserId) {
    $conn = dbconnect();

    // SQL query to join servicedetails with services, and then join with users twice (once for customer, once for arranger)
    $sql = "SELECT sd.*,
                   cust.first_name AS customer_first_name, cust.last_name AS customer_last_name, cust.profile_img AS customer_profile, cust.address AS customer_address, cust.phone AS customer_phone,
                   arr.first_name AS arranger_first_name, arr.last_name AS arranger_last_name, arr.profile_img AS arranger_profile, arr.address AS arranger_address, arr.phone AS arranger_phone
            FROM " . $servicedetailsTable . " AS sd
            JOIN " . $servicesTable . " AS s ON sd.service_id = s.service_id
            JOIN " . $usersTable . " AS cust ON sd.customer_id = cust.user_id
            JOIN " . $usersTable . " AS arr ON s.arranger_id = arr.user_id
            WHERE sd.servicedetails_id = :servicedetailsId AND sd.customer_id = :loggedInUserId";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':servicedetailsId', $servicedetailsId, PDO::PARAM_INT);
    $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


//count of orders in arranger
function count_pending_seller_orders($seller_id) {
    $conn = dbconnect();
    $sql = "SELECT COUNT(*) AS order_count
            FROM sales s
            JOIN products p ON s.product_id = p.product_id
            JOIN shops sh ON p.shop_owner = sh.shop_id
            WHERE sh.owner_id = ? AND s.status = 'Pending'";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$seller_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $result['order_count'];
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}

function count_processing_seller_orders($seller_id) {
    $conn = dbconnect();
    $sql = "SELECT COUNT(*) AS order_count
            FROM sales s
            JOIN products p ON s.product_id = p.product_id
            JOIN shops sh ON p.shop_owner = sh.shop_id
            WHERE sh.owner_id = ? AND s.status = 'Processing'";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$seller_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $result['order_count'];
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}

function count_intransit_seller_orders($seller_id) {
    $conn = dbconnect();
    $sql = "SELECT COUNT(*) AS order_count
            FROM sales s
            JOIN products p ON s.product_id = p.product_id
            JOIN shops sh ON p.shop_owner = sh.shop_id
            WHERE sh.owner_id = ? AND s.status = 'Intransit'";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$seller_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $result['order_count'];
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}
//customer side order (count)
function countCustomerProducts($customerId) {
    $conn = dbconnect();
    $sql = "SELECT COUNT(DISTINCT s.salesdetails_id) AS pending_salesdetails_count
            FROM sales s
            JOIN salesdetails sd ON s.salesdetails_id = sd.salesdetails_id
            WHERE s.customer_id = ? AND s.status = 'Pending'";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$customerId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $result['pending_salesdetails_count'];
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}
//process
function countProcessing($customerId) {
    $conn = dbconnect();
    $sql = "SELECT COUNT(DISTINCT s.salesdetails_id) AS pending_salesdetails_count
            FROM sales s
            JOIN salesdetails sd ON s.salesdetails_id = sd.salesdetails_id
            WHERE s.customer_id = ? AND s.status = 'Processing'";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$customerId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $result['pending_salesdetails_count'];
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}

function countIntransit($customerId) {
    $conn = dbconnect();
    $sql = "SELECT COUNT(DISTINCT s.salesdetails_id) AS pending_salesdetails_count
            FROM sales s
            JOIN salesdetails sd ON s.salesdetails_id = sd.salesdetails_id
            WHERE s.customer_id = ? AND s.status = 'Intransit'";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$customerId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $result['pending_salesdetails_count'];
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}

function countCompleted($customerId) {
    $conn = dbconnect();
    $sql = "SELECT COUNT(DISTINCT s.salesdetails_id) AS pending_salesdetails_count
            FROM sales s
            JOIN salesdetails sd ON s.salesdetails_id = sd.salesdetails_id
            WHERE s.customer_id = ? AND s.status = 'Completed'";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$customerId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $result['pending_salesdetails_count'];
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}


function count_completed_seller_orders($seller_id) {
    $conn = dbconnect();
    $sql = "SELECT COUNT(*) AS order_count
            FROM sales s
            JOIN products p ON s.product_id = p.product_id
            JOIN shops sh ON p.shop_owner = sh.shop_id
            WHERE sh.owner_id = ? AND s.status = 'completed'";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$seller_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $result['order_count'];
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        $conn = null;
        return false;
    }
}

function fetchAllExceptAdmin($tableName) {
    try {
        // Assuming you have a function dbconnect() that returns a PDO connection
        $conn = dbconnect(); 

        // Prepare the SQL statement
        $stmt = $conn->prepare("SELECT * FROM $tableName WHERE role != :excludeRole");

        // Execute the query with the role to exclude
        $stmt->execute(['excludeRole' => 'admin']);

        // Fetch all results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle any errors here
        die("Database error: " . $e->getMessage());
    }
}

function fetchAllAdmin($tableName) {
    try {
        // Assuming you have a function dbconnect() that returns a PDO connection
        $conn = dbconnect(); 

        // Prepare the SQL statement
        $stmt = $conn->prepare("SELECT * FROM $tableName WHERE role = :Role");

        // Execute the query with the role to exclude
        $stmt->execute(['Role' => 'admin']);

        // Fetch all results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle any errors here
        die("Database error: " . $e->getMessage());
    }
}


function get_services_with_images($user_id, $pdo) {
    // Define the SQL query
    $sql = "SELECT s.*, g.image FROM services s
            LEFT JOIN gallery g ON s.service_id = g.service_id
            WHERE s.arranger_id = :user_id";

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // Fetch the services with associated images and return them
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}











    

    
    
    
    
    
    
    
?>

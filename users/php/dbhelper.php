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
		header("Location: login.php");
		die;
	}else{
		return false;
	}
	
}

function generateUniqueFileName($originalFileName) {
    $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
    return uniqid() . '.' . $extension;
}


//add
function get_products_by_seller($user_id) {
    $conn = dbconnect();
    if (!$conn) return array();

    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE seller_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching products: " . $e->getMessage();
        return array();
    } finally {
        $conn = null;
    }
}

//add
function get_products_by_arranger($user_id) {
    $conn = dbconnect();
    if (!$conn) return array();

    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE arranger_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching products: " . $e->getMessage();
        return array();
    } finally {
        $conn = null;
    }
}

//before clicking the shop
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

?>

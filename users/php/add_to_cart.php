<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"));

    // Extract data from the JSON
    $productId = $data->productId;
    $customerId = $data->customerId;
    $flowerType = $data->flowerType;
    $ribbonColor = $data->ribbonColor;
    $message = $data->message;

    // Connect to the database (assuming you have a database connection already)

    try {
        // Prepare and execute the SQL query to insert data into the 'sales_details' table
        $sql = "INSERT INTO salesdetails (product_id, customer_id, flower_type, ribbon_color, message) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$productId, $customerId, $flowerType, $ribbonColor, $message]);

        // If the insertion is successful, return a success response
        $response = ["success" => true];
        echo json_encode($response);
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
} else {
    // Return an error response for invalid requests
    $response = ["success" => false];
    echo json_encode($response);
}

?>

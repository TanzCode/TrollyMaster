<?php
session_start();
require_once 'db_connection.php'; // Ensure this file includes the database connection details

// Check if the session variables for the seller are set
if (!isset($_SESSION['sellerID'])) {
    echo "Seller not logged in.";
    exit;
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input
    $productID = filter_input(INPUT_POST, 'productID', FILTER_SANITIZE_NUMBER_INT);
    $discountPercentage = filter_input(INPUT_POST, 'discountPercentage', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 100]]);
    $promotionDescription = filter_input(INPUT_POST, 'promotionDescription', FILTER_SANITIZE_STRING);
    $startDate = filter_input(INPUT_POST, 'startDate', FILTER_SANITIZE_STRING);
    $endDate = filter_input(INPUT_POST, 'endDate', FILTER_SANITIZE_STRING);

    // Check for required fields and validate date format
    if ($productID && $discountPercentage && $promotionDescription && $startDate && $endDate) {
        // Prepare SQL query to insert the promotion data
        $query = "INSERT INTO promotions (product_id, seller_id, discount_percentage, description, start_date, end_date)
                  VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($query)) {
            // Bind parameters
            $stmt->bind_param("iiisss", $productID, $_SESSION['sellerID'], $discountPercentage, $promotionDescription, $startDate, $endDate);

            // Execute the query
            if ($stmt->execute()) {
                echo "Promotion added successfully.";
                // Redirect to the manage promotions page or reload the page
                header("Location: promotionDiscount.php");
                exit;
            } else {
                echo "Error: Could not execute the query. " . $stmt->error;
            }
            // Close the statement
            $stmt->close();
        } else {
            echo "Error: Could not prepare the statement. " . $conn->error;
        }
    } else {
        echo "Error: Invalid input. Please ensure all fields are correctly filled.";
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
?>

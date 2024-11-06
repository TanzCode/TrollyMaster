<?php
session_start();
include 'dbConnection.php';
// Check if the update order button was clicked
if (isset($_POST['updateOrder'])) {
    $cartItemID = $_POST['cartItemID']; // Get the cart item ID

    // Prepare the SQL query to update the order status to 'Dispatched'
    $updateQuery = "UPDATE cart SET orderStatus = 'Dispatched' WHERE cartItemID = ?";
    
    if ($stmt = $conn->prepare($updateQuery)) {
        $stmt->bind_param('i', $cartItemID); // Bind the cartItemID parameter
        $stmt->execute(); // Execute the query

        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            echo "Order status updated to 'Dispatched'.";
            header("Location: orderManagement.php");
        } else {
            echo "Failed to update order status.";
        }
    } else {
        echo "Error in preparing statement.";
    }
}
?>

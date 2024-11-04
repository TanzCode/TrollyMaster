<?php
session_start();
include('conn.php');

// Check if the seller ID is set in the query string
if (isset($_GET['id'])) {
    $sellerID = $_GET['id'];

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("DELETE FROM seller_personal WHERE sellerID = ?");
    $stmt->bind_param("i", $sellerID);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the seller list page with a success message
        $_SESSION['message'] = "Seller deleted successfully!";
        header("Location: viewAllSeller.php");
    } else {
        // Redirect to the seller list page with an error message
        $_SESSION['message'] = "Error deleting seller!";
        header("Location: viewAllSeller.php");
    }

    // Close the statement
    $stmt->close();
} else {
    // Redirect to the seller list page with an error message
    $_SESSION['message'] = "Invalid seller ID!";
    header("Location: viewAllSeller.php");
}

// Close the database connection
$conn->close();
?>

<?php
session_start();
include('conn.php');

// Check if the store ID is set in the query string
if (isset($_GET['id'])) {
    $regID = $_GET['id'];

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("DELETE FROM store WHERE regID = ?");
    $stmt->bind_param("i", $regID);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the store list page with a success message
        $_SESSION['message'] = "Store deleted successfully!";
        header("Location: viewAllShop.php");
    } else {
        // Redirect to the store list page with an error message
        $_SESSION['message'] = "Error deleting store!";
        header("Location: viewAllShop.php");
    }

    // Close the statement
    $stmt->close();
} else {
    // Redirect to the store list page with an error message
    $_SESSION['message'] = "Invalid store ID!";
    header("Location: viewAllShop.php");
}

// Close the database connection
$conn->close();
?>

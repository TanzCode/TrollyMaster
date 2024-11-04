<?php
session_start();
include('conn.php');

// Check if the customer ID is set in the query string
if (isset($_GET['id'])) {
    $cusID = $_GET['id'];

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("DELETE FROM customer_personal WHERE cusID = ?");
    $stmt->bind_param("i", $cusID);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the customer list page with a success message
        $_SESSION['message'] = "Customer deleted successfully!";
        header("Location: viewCustomer.php");
    } else {
        // Redirect to the customer list page with an error message
        $_SESSION['message'] = "Error deleting customer!";
        header("Location: viewCustomer.php");
    }

    // Close the statement
    $stmt->close();
} else {
    // Redirect to the customer list page with an error message
    $_SESSION['message'] = "Invalid customer ID!";
    header("Location: viewCustomer.php");
}

// Close the database connection
$conn->close();
?>

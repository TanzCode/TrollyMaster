<?php
session_start();
include 'dbConnection.php'; // Ensure this file contains the connection setup

// Check if the session variables for the seller are set
if (isset($_SESSION['sellerID'])) {
    $sellerID = $_SESSION['sellerID'];
} else {
    echo "Seller not logged in.";
    header("Location: ../login.html");
    exit();
}

// Get today's date to check for expired promotions
$currentDate = date('Y-m-d');

// Step 1: Update expired promotions
$updatePromotionSQL = "UPDATE promotions 
                       SET promotionDetails = 'expired' 
                       WHERE endDate < '$currentDate' AND promotionDetails != 'expired'";

if (mysqli_query($conn, $updatePromotionSQL)) {
    echo "Expired promotions updated successfully.<br>";
} else {
    echo "Error updating expired promotions: " . mysqli_error($conn) . "<br>";
}

// Step 2: Update product discounts for active promotions
$updateProductDiscountSQL = "UPDATE product p
                             JOIN promotions pr ON p.promotionID = pr.promotionID
                             SET p.discounts = pr.discount
                             WHERE pr.endDate >= '$currentDate' AND pr.promotionDetails != 'expired'";

if (mysqli_query($conn, $updateProductDiscountSQL)) {
    echo "Product discounts updated successfully.<br>";
} else {
    echo "Error updating product discounts: " . mysqli_error($conn) . "<br>";
}

// Close connection
mysqli_close($conn);
?>

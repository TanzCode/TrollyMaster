<?php
session_start();
include 'dbConnection.php';

// Check if seller is logged in
if (!isset($_SESSION['sellerID'])) {
    echo "Access denied.";
    header("Location: login.php");
    exit();
}

$sellerID = $_SESSION['sellerID'];

// Process each product's discount and special details updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['discount'] as $productID => $discount) {
        $specialDetails = $_POST['specialDetails'][$productID];

        // Update discount and promotion details for each product
        $updateSql = "UPDATE product SET discounts = '$discount', specialDetails = '$specialDetails' WHERE productID = '$productID' AND sellerID = '$sellerID'";
        mysqli_query($conn, $updateSql);
    }

    echo "Promotions updated successfully.";
    header("Location: promotions.php");
    exit();
}

mysqli_close($conn);
?>

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

// Check if form data is set
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $promotionName = mysqli_real_escape_string($conn, $_POST['promotionName']);
    $promotionDetails = mysqli_real_escape_string($conn, $_POST['promotionDetails']);
    $discount = $_POST['discount'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Insert promotion into the promotions table
    $insertPromotionSQL = "INSERT INTO promotions (promotionName, promotionDetails, discount, startDate, endDate)
                           VALUES ('$promotionName', '$promotionDetails', '$discount', '$startDate', '$endDate')";

    if (mysqli_query($conn, $insertPromotionSQL)) {
        $promotionID = mysqli_insert_id($conn); // Get the last inserted promotion ID
        
        // Check if products are selected for the promotion
        if (isset($_POST['products']) && is_array($_POST['products'])) {
            $selectedProducts = $_POST['products'];

            // Update the selected products with the promotionID
            foreach ($selectedProducts as $productID) {
                $updateProductSQL = "UPDATE product SET promotionID = '$promotionID', discounts='$discount' WHERE productID = '$productID'";

                // Check if the query was successful
                if (mysqli_query($conn, $updateProductSQL)) {
                    echo "Product with ID $productID linked to promotion.<br>";
                } else {
                    echo "Error updating product with ID $productID: " . mysqli_error($conn) . "<br>";
                }
            }
            echo "Promotion created successfully and products linked.<br>";
        } else {
            echo "No products selected for the promotion.<br>";
        }
    } else {
        echo "Error creating promotion: " . mysqli_error($conn) . "<br>";
    }
}

mysqli_close($conn);
?>

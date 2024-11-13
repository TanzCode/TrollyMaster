<?php
include 'dbConnection.php';

if (isset($_POST['cartItemID']) && isset($_POST['quantity'])) {
    $cartItemID = $_POST['cartItemID'];
    $newQuantity = $_POST['quantity'];

    // Fetch the old quantity and product ID from the cart before updating
    $query = "SELECT productID, quantity FROM cart WHERE cartItemID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cartItemID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $productID = $row['productID'];
        $oldQuantity = $row['quantity'];

        // Calculate the quantity difference
        $quantityDifference = $newQuantity - $oldQuantity;

        // Update the product's quantity accordingly
        $updateProductQuery = "UPDATE product SET stockAmount = stockAmount - ? WHERE productID = ?";
        $stmt2 = $conn->prepare($updateProductQuery);
        $stmt2->bind_param("ii", $quantityDifference, $productID);

        if ($stmt2->execute()) {
            // Now update the cart with the new quantity
            $updateCartQuery = "UPDATE cart SET quantity = ? WHERE cartItemID = ?";
            $stmt3 = $conn->prepare($updateCartQuery);
            $stmt3->bind_param("ii", $newQuantity, $cartItemID);

            if ($stmt3->execute()) {
                echo 'success'; // Successfully updated both cart and product
            } else {
                echo 'error'; // Failed to update the cart
            }
        } else {
            echo 'error'; // Failed to update the product quantity
        }
    } else {
        echo 'error'; // Failed to fetch the cart item details
    }
}
?>

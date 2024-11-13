<?php
include 'dbConnection.php';

if (isset($_POST['cartItemID'])) {
    $cartItemID = $_POST['cartItemID'];

    // Fetch the product ID and quantity of the item being removed from the cart
    $query = "SELECT productID, quantity FROM cart WHERE cartItemID = $cartItemID";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $productID = $row['productID'];
        $quantityToRestore = $row['quantity'];

        // Remove the item from the cart
        $deleteQuery = "DELETE FROM cart WHERE cartItemID = $cartItemID";
        if (mysqli_query($conn, $deleteQuery)) {
            // Update the product table by adding back the quantity to the stock
            $updateProductQuery = "UPDATE product SET stockAmount = stockAmount + ? WHERE productID = ?";
            $stmt = $conn->prepare($updateProductQuery);
            $stmt->bind_param("ii", $quantityToRestore, $productID);
            
            if ($stmt->execute()) {
                echo 'success'; // Successfully updated
            } else {
                echo 'error'; // Failed to update the product table
            }
        } else {
            echo 'error'; // Failed to remove from the cart
        }
    } else {
        echo 'error'; // Failed to fetch the cart item details
    }
}
?>

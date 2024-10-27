<?php
$cusID = $_SESSION['cusID']; // Assuming session stores customer ID
$query = "
    SELECT c.cartItemID, c.productID, c.quantity, p.sellerID 
    FROM cart c 
    JOIN product p ON c.productID = p.productID 
    WHERE c.cusID = $cusID AND c.status = 0
";
$result = mysqli_query($conn, $query);

$cartItems = [];

while ($row = mysqli_fetch_assoc($result)) {
    $cartItems[] = $row;
    
    // Prepare notification for the seller
    $sellerID = $row['sellerID'];
    $message = "You have a new order from  " . $row['cusID'] . " for product ID: " . $row['productID'] . ", Quantity: " . $row['quantity'];
    
    // Insert notification into the notifications table
    $notificationQuery = "
        INSERT INTO notifications (recipientType, recipientID, Message, status, timeStamp) 
        VALUES ('seller', $sellerID, '$message', 0, NOW())
    ";
    mysqli_query($conn, $notificationQuery);
}

// Output cart items as JSON for the frontend
echo json_encode($cartItems);
?>

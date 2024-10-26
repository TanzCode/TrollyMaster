<?php
if (isset($_GET['orderID'])) {
    $orderID = $_GET['orderID'];
} else {
    echo "No order ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Thank You!</h1>
    <p>Your order has been placed successfully.</p>
    <p>Your Order ID is: <strong><?php echo $orderID; ?></strong></p>
    <p>You will receive an email confirmation shortly.</p>
    <a href="../index.php" class="btn btn-primary">Continue Shopping</a>
</div>
</body>
</html>

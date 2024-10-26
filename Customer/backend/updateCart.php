<?php
include 'dbConnection.php';

if (isset($_POST['cartItemID']) && isset($_POST['quantity'])) {
    $cartItemID = $_POST['cartItemID'];
    $quantity = $_POST['quantity'];

    $query = "UPDATE cart SET quantity = $quantity WHERE cartItemID = $cartItemID";
    if (mysqli_query($conn, $query)) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>

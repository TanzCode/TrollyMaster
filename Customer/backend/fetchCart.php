<?php
session_start();
include 'dbConnection.php';

$cusID = $_SESSION['cusID']; // Assuming session stores customer ID
$query = "SELECT * FROM cart WHERE cusID = $cusID AND status = 0";
$result = mysqli_query($conn, $query);

$cartItems = [];

while ($row = mysqli_fetch_assoc($result)) {
    $cartItems[] = $row;
}

echo json_encode($cartItems);
?>

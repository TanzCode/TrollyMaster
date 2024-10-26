<?php
include 'dbConnection.php';

if (isset($_POST['cartItemID'])) {
    $cartItemID = $_POST['cartItemID'];

    $query = "DELETE FROM cart WHERE cartItemID = $cartItemID";
    if (mysqli_query($conn, $query)) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>

<?php
session_start();
include('conn.php');

if (isset($_GET['id'])) {
    $productID = $_GET['id'];

    $sql = "DELETE FROM product WHERE productID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $productID);
        if ($stmt->execute()) {
            echo "Product successfully deleted.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

$conn->close();
?>

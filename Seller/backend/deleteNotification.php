<?php
require 'dbConnection.php'; // Include your database connection file

if (isset($_POST['notificationID'])) {
    $notificationID = $_POST['notificationID'];

    // Delete the notification
    $sql = "DELETE FROM notifications WHERE notificationID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $notificationID);
    
    if ($stmt->execute()) {
        echo 'success'; // Success response
    } else {
        echo 'error'; // Failure response
    }
}
?>

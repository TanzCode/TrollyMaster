<?php
require 'dbConnection.php'; // Include your database connection file

if (isset($_POST['notificationID'])) {
    $notificationID = $_POST['notificationID'];

    // Update the notification status to 'read' (status = 1)
    $sql = "UPDATE notifications SET status = 1 WHERE notificationID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $notificationID);
    
    if ($stmt->execute()) {
        echo 'success'; // Success response
    } else {
        echo 'error'; // Failure response
    }
}
?>

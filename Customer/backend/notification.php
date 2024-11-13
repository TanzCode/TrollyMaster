<?php
session_start();
require 'dbConnection.php'; // Include your database connection file

// Check if the customer is logged in
if (isset($_SESSION['loggedincus']) && $_SESSION['loggedincus'] == true) {
    $userID = htmlspecialchars($_SESSION['cusID']);
    $firstName = $_SESSION['fname'];
    $lastName = $_SESSION['lname'];



    // Fetch notifications from the database
    $notifications = [];
    $sql = "SELECT * FROM notifications WHERE recipientType = 'customer' AND recipientID = ? ORDER BY timeStamp DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID); // Bind the customer ID
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row; // Store notifications in an array
        }
    }
} else {
    echo "First name not set.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="CSS/slider.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard - Seller</title>
    <style>
        .notifications-container {
            margin-top: 80px;
        }
        .notification-card {
            background-color: #f8f9fa;
            border-left: 5px solid #007bff;
            border-radius: 8px;
            margin-bottom: 15px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }
        .notification-card.unread {
            border-left-color: #28a745;
        }
        .notification-card:hover {
            background-color: #e9ecef;
            transform: translateY(-5px);
        }
        .notification-time {
            color: #6c757d;
            font-size: 0.9em;
        }
        .notification-message {
            font-size: 1.2em;
            margin-bottom: 0;
        }
        .status-badge {
            padding: 0.5em 1em;
            font-size: 0.9em;
            border-radius: 12px;
        }
        .status-unread {
            background-color: #28a745;
            color: white;
        }
        .status-read {
            background-color: #6c757d;
            color: white;
        }
        .mark-read-btn, .delete-btn {
            margin-top: 10px;
            padding: 0.25rem 0.75rem;
            font-size: 0.8rem;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
        }
    </style>
</head>
<body>

        <!-- Notifications Section -->
        <div class="notifications-container container">
            <h2>Your Notifications</h2>
            <?php if (!empty($notifications)) : ?>
                <?php foreach ($notifications as $notification) : ?>
                    <div class="notification-card <?php echo ($notification['status'] == 0) ? 'unread' : 'read'; ?>">
                        <div class="d-flex justify-content-between">
                            <div class="notification-message">
                                <i class='bx bx-bell'></i> <?php echo htmlspecialchars($notification['Message']); ?>
                            </div>
                            <span class="status-badge <?php echo ($notification['status'] == 0) ? 'status-unread' : 'status-read'; ?>">
                                <?php echo ($notification['status'] == 0) ? 'Unread' : 'Read'; ?>
                            </span>
                        </div>
                        <div class="notification-time">
                            <i class='bx bx-time'></i> <?php echo htmlspecialchars($notification['timeStamp']); ?>
                        </div>
                        <!-- Mark as Read Button (for unread notifications only) -->
                        <?php if ($notification['status'] == 0) : ?>
                            <button class="btn btn-success mark-read-btn" data-id="<?php echo $notification['notificationID']; ?>">Mark as Read</button>
                        <?php endif; ?>
                        <!-- Delete Button -->
                        <button class="btn delete-btn" data-id="<?php echo $notification['notificationID']; ?>">Delete</button>

                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No notifications found.</p>
            <?php endif; ?>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle the Mark as Read button click
            $('.mark-read-btn').on('click', function() {
                var notificationID = $(this).data('id');
                
                // Send AJAX request to update the notification status
                $.ajax({
                    url: 'updateNotification.php',
                    method: 'POST',
                    data: { notificationID: notificationID },
                    success: function(response) {
                        if (response === 'success') {
                            // Reload the page to reflect the updated notification status
                            location.reload();
                        } else {
                            alert('Failed to update notification status.');
                        }
                    }
                });
            });
        });
        // Handle the Delete button click
        $('.delete-btn').on('click', function() {
                var notificationID = $(this).data('id');
                
                // Send AJAX request to delete the notification
                $.ajax({
                    url: 'deleteNotification.php',
                    method: 'POST',
                    data: { notificationID: notificationID },
                    success: function(response) {
                        if (response === 'success') {
                            // Reload the page to reflect the updated notification list
                            location.reload();
                        } else {
                            alert('Failed to delete notification.');
                        }
                    }
                });
            });
        
    </script>
</body>
</html>

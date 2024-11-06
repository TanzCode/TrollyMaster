<?php
session_start();
require 'dbConnection.php'; // Include your database connection file

// Check if the seller is logged in
if (isset($_SESSION['fName']) && isset($_SESSION['lName']) && isset($_SESSION['sellerID'])) {
    $firstName = $_SESSION['fName'];
    $lastName = $_SESSION['lName'];
    $userID = $_SESSION['sellerID'];

    // Fetch notifications from the database
    $notifications = [];
    $sql = "SELECT * FROM notifications WHERE recipientType = 'seller' AND recipientID = ? ORDER BY timeStamp DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID); // Bind the seller ID
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
<body id="body-pd">
    <header class="header" id="header">
        <div class="header_toggle">
            <i class='bx bx-menu' id="header-toggle"></i>
        </div>
        <div class="header_img">
            <img src="pro.jpg" alt="Profile Image">
        </div>
    </header>
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="overview.php" class="nav_logo">
                    <i class='bx bx-layer nav_logo-icon'></i>
                    <span class="nav_logo-name">Trolly Master</span>
                </a>
                <div class="nav_list">
                    <a href="overview.php" class="nav_link">
                        <i class='bx bx-grid-alt nav_icon'></i>
                        <span class="nav_name">Overview</span>
                    </a>
                    <div class="nav_item dropdown">
                        <a href="#" class="nav_link dropdown-toggle" id="customerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class='bx bx-clipboard' ></i>
                            <span class="nav_name">Product</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="customerDropdown">
                            <li><a class="dropdown-item" href="product/addProducts.php"><i class='bx bxs-add-to-queue'></i><span class="nav_name">Add new Product</span></a></li>
                            <li><a class="dropdown-item" href="product/updateProducts.php" ><i class='bx bxs-book-open' ></i><span class="nav_name">View all products</span></a></li>
                            <li><a class="dropdown-item" href="product/viewProducts.php"><i class='bx bx-receipt'></i><span class="nav_name">Update product list</span></a></li>
                        </ul>
                    </div>
                    <a href="orderManagement.php"  class="nav_link">
                        <i class='bx bxs-package' ></i><span class="nav_name">Orders</span>
                    </a>

                    <a href="shopManagement.php" class="nav_link">
                        <i class='bx bx-store'></i><span class="nav_name">Shop management</span>
                    </a>
                    <a href="promotions.php" class="nav_link">
                        <i class='bx bxs-gift'></i><span class="nav_name">Promotions</span>
                    </a>
                    <a href="notification.php" class="nav_link"  class="nav_link active">
                        <i class='bx bx-message-square-detail nav_icon'></i><span class="nav_name" >Notification</span>
                    </a>
                    <a href="profile.php" class="nav_link">
                        <i class='bx bx-cog' ></i><span class="nav_name">Profile management</span>
                    </a>
                    
                </div>
            </div>
            <a href="../login.html" class="nav_link">
                <i class='bx bx-log-out nav_icon'></i><span class="nav_name">Sign Out</span>
            </a>
        </nav>
    </div>

    <!-- Container Main start -->
  <!-- Container Main start -->
    <div class="height-100 bg-light">
        <div class="welcome-message" style="margin-top:80px;">
            <h1>Welcome, <?php echo htmlspecialchars($firstName) . " " . htmlspecialchars($lastName); ?>!</h1>
            <p>Seller ID: <span id="user-id"><?php echo htmlspecialchars($userID); ?></span></p>
        </div>
        <hr>

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
    </div>
    <!-- Container Main end -->

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

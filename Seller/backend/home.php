<?php
// Include your database connection
include 'dbConnection.php';

session_start();

// Check if the session variables for the seller are set
if (isset($_SESSION['fName']) && isset($_SESSION['lName']) && isset($_SESSION['sellerID'])) {
    $firstName = $_SESSION['fName'];
    $lastName = $_SESSION['lName'];
    $sellerID = $_SESSION['sellerID'];
} else {
    echo "Seller not logged in.";
    header("Location: ../login.html");
    exit();
}

// Fetch daily order count per seller by joining `cart` with `product` table
$query_orders = "
    SELECT 
        COUNT(*) AS daily_order_count, 
        p.sellerID, 
        DATE(c.createdAt) AS order_date 
    FROM 
        cart c
    JOIN 
        product p ON c.productID = p.productID
    where 
        p.sellerID=$sellerID
    GROUP BY 
         DATE(c.createdAt)
";
$result_orders = mysqli_query($conn, $query_orders);
if (!$result_orders) {
    die("Error executing query: " . mysqli_error($conn));
}

$orders_data = [];
while ($row = mysqli_fetch_assoc($result_orders)) {
    $orders_data[] = $row;
}

// Fetch daily cart items count per seller
$query_cart_items = "
    SELECT 
        COUNT(*) AS daily_cart_count, 
        p.sellerID, 
        DATE(c.createdAt) AS cart_date 
    FROM 
        cart c
    JOIN 
        product p ON c.productID = p.productID
    GROUP BY 
        p.sellerID, DATE(c.createdAt)
";
$result_cart_items = mysqli_query($conn, $query_cart_items);
if (!$result_cart_items) {
    die("Error executing query: " . mysqli_error($conn));
}

$cart_items_data = [];
while ($row = mysqli_fetch_assoc($result_cart_items)) {
    $cart_items_data[] = $row;
}

// Fetch daily revenue by main categories from the cart table for each seller
$query_daily_revenue_categories = "
    SELECT 
        c.productCatogory, 
        p.sellerID, 
        SUM(c.total) AS daily_revenue, 
        DATE(c.createdAt) AS revenue_date
    FROM 
        cart c
    JOIN 
        product p ON c.productID = p.productID
    where p.sellerID = $sellerID
    GROUP BY 
        c.productCatogory,  DATE(c.createdAt)
";
$result_daily_revenue_categories = mysqli_query($conn, $query_daily_revenue_categories);
if (!$result_daily_revenue_categories) {
    die("Error executing query: " . mysqli_error($conn));
}

$daily_revenue_categories_data = [];
while ($row = mysqli_fetch_assoc($result_daily_revenue_categories)) {
    $daily_revenue_categories_data[] = $row;
}

// Fetch total sellers
$query_sellers = "SELECT COUNT(*) AS total_sellers FROM seller_personal";
$result_sellers = mysqli_query($conn, $query_sellers);
if (!$result_sellers) {
    die("Error executing query: " . mysqli_error($conn));
}

$total_sellers = mysqli_fetch_assoc($result_sellers)['total_sellers'];

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.css" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #FF5722;
        }

        .dashboard-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            padding: 25px;
        }

        .card {
            padding: 25px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .chart-container {
            position: relative;
            height: 400px;
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
                <a href="home.php" class="nav_logo">
                    <i class='bx bx-layer nav_logo-icon'></i>
                    <span class="nav_logo-name">Trolly Master</span>
                </a>
                <div class="nav_list">
                    <a href="overview.php" class="nav_link">
                        <i class='bx bx-grid-alt nav_icon'></i>
                        <span class="nav_name">Overview</span>
                    </a>
                    <div class="nav_item dropdown">
                        <a href="#" class="nav_link dropdown-toggle" id="productDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class='bx bx-clipboard'></i>
                            <span class="nav_name">Product</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="productDropdown">
                            <li><a class="dropdown-item" href="product/addProducts.php"><i class='bx bxs-add-to-queue'></i><span class="nav_name">Add new Product</span></a></li>
                            <li><a class="dropdown-item" href="product/updateProducts.php"><i class='bx bxs-book-open'></i><span class="nav_name">View all products</span></a></li>
                            <li><a class="dropdown-item" href="product/viewProducts.php"><i class='bx bx-receipt'></i><span class="nav_name">Update product list</span></a></li>
                        </ul>
                    </div>
                    <a href="orderManagement.php" class="nav_link">
                        <i class='bx bxs-package'></i><span class="nav_name">Orders</span>
                    </a>
                    <a href="shopManagement.php" class="nav_link">
                        <i class='bx bx-store'></i><span class="nav_name">Shop management</span>
                    </a>
                    <a href="notification.php" class="nav_link active">
                        <i class='bx bx-message-square-detail nav_icon'></i><span class="nav_name">Notification</span>
                    </a>
                    <a href="profile.php" class="nav_link">
                        <i class='bx bx-cog'></i><span class="nav_name">Profile management</span></a>
                </div>
            </div>
            <a href="login.html" class="nav_link">
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


        <div class="notifications-container container">
    <h1>Selelr Dashboard</h1>

    <div class="dashboard-container">
        <div class="card">
            <h2>Total Sellers</h2>
            <p><?php echo $total_sellers; ?></p>
        </div>

        <div class="card">
            <h2>Total Orders</h2>
            <p><?php echo array_sum(array_column($orders_data, 'daily_order_count')); ?></p>
        </div>

        <div class="card">
            <h2>Daily Order Count</h2>
            <div class="chart-container">
                <canvas id="orderCountChart"></canvas>
            </div>
        </div>

        <div class="card">
            <h2>Daily Cart Items</h2>
            <div class="chart-container">
                <canvas id="cartItemsChart"></canvas>
            </div>
        </div>

        <div class="card">
            <h2>Daily Revenue by Categories</h2>
            <div class="chart-container">
                <canvas id="dailyRevenueCategoriesChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Data Preparation
        const orderDates = <?php echo json_encode(array_column($orders_data, 'order_date')); ?>;
        const orderCounts = <?php echo json_encode(array_column($orders_data, 'daily_order_count')); ?>;
        
        const cartDates = <?php echo json_encode(array_column($cart_items_data, 'cart_date')); ?>;
        const cartCounts = <?php echo json_encode(array_column($cart_items_data, 'daily_cart_count')); ?>;
        
        const revenueDates = <?php echo json_encode(array_column($daily_revenue_categories_data, 'revenue_date')); ?>;
        const revenues = <?php echo json_encode(array_column($daily_revenue_categories_data, 'daily_revenue')); ?>;

        // Order Count Chart
        const orderCountCtx = document.getElementById('orderCountChart').getContext('2d');
        const orderCountChart = new Chart(orderCountCtx, {
            type: 'line',
            data: {
                labels: orderDates,
                datasets: [{
                    label: 'Daily Orders',
                    data: orderCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Cart Items Chart
        const cartItemsCtx = document.getElementById('cartItemsChart').getContext('2d');
        const cartItemsChart = new Chart(cartItemsCtx, {
            type: 'bar',
            data: {
                labels: cartDates,
                datasets: [{
                    label: 'Daily Cart Items',
                    data: cartCounts,
                    backgroundColor: 'rgba(153, 102, 255, 0.4)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Daily Revenue by Categories Chart
        const dailyRevenueCategoriesCtx = document.getElementById('dailyRevenueCategoriesChart').getContext('2d');
        const dailyRevenueCategoriesChart = new Chart(dailyRevenueCategoriesCtx, {
            type: 'bar',
            data: {
                labels: revenueDates,
                datasets: [{
                    label: 'Revenue by Category',
                    data: revenues,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>

    </body>
    </html>

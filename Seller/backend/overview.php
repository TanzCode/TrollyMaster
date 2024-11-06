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
    WHERE 
        p.sellerID = ?
    GROUP BY 
        DATE(c.createdAt)
";

$stmt_orders = $conn->prepare($query_orders);

// Check if the prepare() fails
if (!$stmt_orders) {
    die("Error preparing statement for orders: " . $conn->error);
}

$stmt_orders->bind_param("i", $sellerID);  // Bind the sellerID parameter
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();

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
    WHERE 
        p.sellerID = ?
    GROUP BY 
        p.sellerID, DATE(c.createdAt)
";

$stmt_cart_items = $conn->prepare($query_cart_items);

// Check if the prepare() fails
if (!$stmt_cart_items) {
    die("Error preparing statement for cart items: " . $conn->error);
}

$stmt_cart_items->bind_param("i", $sellerID);  // Bind the sellerID parameter
$stmt_cart_items->execute();
$result_cart_items = $stmt_cart_items->get_result();

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
    WHERE 
        p.sellerID = ?
    GROUP BY 
        c.productCatogory, DATE(c.createdAt)
";

$stmt_daily_revenue_categories = $conn->prepare($query_daily_revenue_categories);

// Check if the prepare() fails
if (!$stmt_daily_revenue_categories) {
    die("Error preparing statement for daily revenue categories: " . $conn->error);
}

$stmt_daily_revenue_categories->bind_param("i", $sellerID);  // Bind the sellerID parameter
$stmt_daily_revenue_categories->execute();
$result_daily_revenue_categories = $stmt_daily_revenue_categories->get_result();

$daily_revenue_categories_data = [];
while ($row = mysqli_fetch_assoc($result_daily_revenue_categories)) {
    $daily_revenue_categories_data[] = $row;
}

// Fetch total revenue by categories for the specific seller
$query_revenue_by_categories = "
    SELECT 
        c.productCatogory, 
        SUM(c.total) AS total_revenue
    FROM 
        cart c
    JOIN 
        product p ON c.productID = p.productID
    WHERE 
        p.sellerID = ?
    GROUP BY 
        c.productCatogory
";

$stmt_revenue_by_categories = $conn->prepare($query_revenue_by_categories);

// Check if the prepare() fails
if (!$stmt_revenue_by_categories) {
    die("Error preparing statement for revenue by categories: " . $conn->error);
}

$stmt_revenue_by_categories->bind_param("i", $sellerID);  // Bind the sellerID parameter
$stmt_revenue_by_categories->execute();
$result_revenue_by_categories = $stmt_revenue_by_categories->get_result();

// Store the revenue data by categories
$revenue_by_categories_data = [];
while ($row = mysqli_fetch_assoc($result_revenue_by_categories)) {
    $revenue_by_categories_data[] = $row;
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
   
    <title>Dashboard - Seller</title>
    <style>
       
       /* Variables for consistent colors */
       :root {
           --dark-orange: #FF5722;
           --light-orange: #FF8A65;
           --dark-green: #2E7D32;
           --light-green: #81C784;
           --accent-orange: #FF7043;
           --accent-green: #4CAF50;
           --background: #F4F4F9; /* Light background */
           --card-bg: #FFFFFF; /* Light card background */
           --text-primary: #333333; /* Darker text for contrast */
           --text-secondary: #666666;
           --chart-line-color: #FF5722; /* Vibrant color for chart lines */
       }

       /* Overall layout */
       body {
           font-family: 'Poppins', sans-serif;
           background-color: var(--background);
           margin: 0;
           padding: 0;
           color: var(--text-primary);
       }

       h1 {
           text-align: center;
           margin-top: 20px;
           color: var(--dark-orange);
           font-size: 2.5em;
           text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1); /* Softer shadow for light background */
           animation: titlePulse 2s infinite;
       }

       @keyframes titlePulse {
           0% { transform: scale(1); }
           50% { transform: scale(1.05); }
           100% { transform: scale(1); }
       }

       .dashboard-container {
           display: grid;
           grid-template-columns: repeat(2, 1fr);
           gap: 25px;
           padding: 25px;
           animation: fadeIn 0.8s ease-in;
       }

       @keyframes fadeIn {
           from { opacity: 0; transform: translateY(20px); }
           to { opacity: 1; transform: translateY(0); }
       }

       .top-summary {
           display: flex;
           justify-content: space-around;
           margin: 25px auto;
           width: 90%;
           max-width: 1200px;
           gap: 25px;
       }

       /* Top summary cards */
       .summary-card {
           flex: 1;
           text-align: center;
           padding: 25px;
           background: var(--card-bg);
           border-radius: 15px;
           box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
           transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
           border: 2px solid transparent;
           position: relative;
           overflow: hidden;
       }

       .summary-card::before {
           content: '';
           position: absolute;
           top: 0;
           left: -100%;
           width: 100%;
           height: 100%;
           background: linear-gradient(
               120deg,
               transparent,
               rgba(0, 0, 0, 0.1),
               transparent
           );
           transition: 0.5s;
       }

       .summary-card:hover::before {
           left: 100%;
       }

       .summary-card:nth-child(odd) {
           border-color: var(--dark-orange);
       }

       .summary-card:nth-child(even) {
           border-color: var(--dark-green);
       }

       .summary-card:hover {
           transform: translateY(-12px) scale(1.02);
           box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
       }

       .summary-card h2 {
           font-size: 2.2em;
           margin: 0;
           background: linear-gradient(45deg, var(--dark-orange), var(--dark-green));
           -webkit-background-clip: text;
           animation: numberPulse 2s infinite;
       }

       @keyframes numberPulse {
           0% { transform: scale(1); }
           50% { transform: scale(1.1); }
           100% { transform: scale(1); }
       }

       .summary-card p {
           font-size: 1.2em;
           margin-top: 8px;
           color: var(--text-secondary);
       }

       /* Chart cards */
       .card {
           padding: 25px;
           background: var(--card-bg);
           border-radius: 15px;
           box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
           transition: all 0.3s ease;
           border: 2px solid transparent;
           animation: slideIn 0.6s ease-out;
       }

       @keyframes slideIn {
           from {
               opacity: 0;
               transform: translateX(-30px);
           }
           to {
               opacity: 1;
               transform: translateX(0);
           }
       }

       .card:hover {
           transform: translateY(-8px);
           box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
           border-color: var(--light-orange);
       }

       .chart-container {
           position: relative;
           height: 400px;
           background: linear-gradient(145deg, 
               rgba(46, 125, 50, 0.1),
               rgba(255, 87, 34, 0.1)
           );
           border-radius: 10px;
           padding: 15px;
       }

       /* Chart specific styles */
       .chart-line {
           stroke: var(--chart-line-color); /* Vibrant line color */
           stroke-width: 3px;
       }

       .chart-label {
           fill: var(--dark-orange); /* Label color for better visibility */
           font-weight: bold;
       }

       /* Responsive design */
       @media (max-width: 768px) {
           .dashboard-container {
               grid-template-columns: 1fr;
           }
           
           .top-summary {
               flex-direction: column;
           }
           
           .summary-card {
               margin-bottom: 15px;
           }
       }
       /* Container */
       .container { max-width: 1200px; padding: 20px; margin: auto; }

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
                    <a href="overview.php" class="nav_link" class="nav_link active">
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
                    <a href="notification.php" class="nav_link">
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
<!--Container Main start-->
<div class="height-100 bg-light" style="margin-top:80px;">
    <div class="welcome-message">
        <h1 style="color:black;">Welcome, <?php echo htmlspecialchars($firstName) . " " . htmlspecialchars($lastName); ?>!</h1>
        <p>Seller ID: <span id="user-id"><?php echo htmlspecialchars($sellerID); ?></span></p>
    </div>
    <hr>
    <div class="container">
        <h1>Seller Dashboard</h1>

        <div class="dashboard-container">
            <!-- Total Sellers -->
            <div class="card">
                <h2>Total Sellers</h2>
                <p><?php echo $total_sellers; ?></p>
            </div>

            <!-- Total Orders -->
            <div class="card">
                <h2>Total Orders</h2>
                <p><?php echo array_sum(array_column($orders_data, 'daily_order_count')); ?></p>
            </div>

            <!-- Daily Order Count -->
            <div class="card">
                <h2>Daily Order Count</h2>
                <div class="chart-container">
                    <canvas id="orderCountChart"></canvas>
                </div>
            </div>

            <!-- Daily Cart Items -->
            <div class="card">
                <h2>Daily Cart Items</h2>
                <div class="chart-container">
                    <canvas id="cartItemsChart"></canvas>
                </div>
            </div>

            <!-- Daily Revenue -->
            <div class="card">
                <h2>Daily Revenue</h2>
                <div class="chart-container">
                    <canvas id="dailyRevenueCategoriesChart"></canvas>
                </div>
            </div>

            <!-- Revenue by Categories -->
            <div class="card">
                <h2>Revenue by Categories</h2>
                <div class="chart-container">
                    <canvas id="revenueByCategoriesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

    <!--Container Main end-->
<!--Container Main end-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    // Data Preparation for charts
    const orderDates = <?php echo json_encode(array_column($orders_data, 'order_date')); ?>;
    const orderCounts = <?php echo json_encode(array_column($orders_data, 'daily_order_count')); ?>;

    const cartDates = <?php echo json_encode(array_column($cart_items_data, 'cart_date')); ?>;
    const cartCounts = <?php echo json_encode(array_column($cart_items_data, 'daily_cart_count')); ?>;

    const revenueDates = <?php echo json_encode(array_column($daily_revenue_categories_data, 'revenue_date')); ?>;
    const dailyRevenues = <?php echo json_encode(array_column($daily_revenue_categories_data, 'daily_revenue')); ?>;

    const categories = <?php echo json_encode(array_column($revenue_by_categories_data, 'productCatogory')); ?>;
    const categoryRevenues = <?php echo json_encode(array_column($revenue_by_categories_data, 'total_revenue')); ?>;

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
                label: 'Daily Revenue by Category',
                data: dailyRevenues,
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

    // Total Revenue by Categories Chart
    const revenueByCategoriesCtx = document.getElementById('revenueByCategoriesChart').getContext('2d');
    const revenueByCategoriesChart = new Chart(revenueByCategoriesCtx, {
        type: 'bar',
        data: {
            labels: categories,
            datasets: [{
                label: 'Total Revenue by Category',
                data: categoryRevenues,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenue (in currency)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Product Categories'
                    }
                }
            }
        }
    });
</script>

<script src="JS/slider.js"></script>
<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

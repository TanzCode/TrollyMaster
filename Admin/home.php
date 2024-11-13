<?php
// Include your database connection
include 'conn.php';

// Fetch daily order count
$query_orders = "SELECT COUNT(*) AS daily_order_count, DATE(createdTime) AS order_date FROM orders GROUP BY DATE(createdTime)";
$result_orders = mysqli_query($conn, $query_orders);
$orders_data = [];
while ($row = mysqli_fetch_assoc($result_orders)) {
    $orders_data[] = $row;
}

// Fetch revenue by store
$query_revenue = "SELECT s.name AS store_name, SUM(o.grandTotal) AS revenue 
                  FROM orders o
                  JOIN product p ON o.orderID = p.productID
                  JOIN store s ON p.storeID = s.regID
                  GROUP BY s.regID";
$result_revenue = mysqli_query($conn, $query_revenue);
$revenue_data = [];
while ($row = mysqli_fetch_assoc($result_revenue)) {
    $revenue_data[] = $row;
}

// Fetch customer registration progress
$query_customers_progress = "SELECT COUNT(*) AS customer_count, DATE(registration_date) AS reg_date 
                             FROM customer_personal GROUP BY DATE(registration_date)";
$result_customers_progress = mysqli_query($conn, $query_customers_progress);
$customer_progress_data = [];
while ($row = mysqli_fetch_assoc($result_customers_progress)) {
    $customer_progress_data[] = $row;
}

// Fetch daily cart items count
$query_cart_items = "SELECT COUNT(*) AS daily_cart_count, DATE(createdAt) AS cart_date 
                     FROM cart GROUP BY DATE(createdAt)";
$result_cart_items = mysqli_query($conn, $query_cart_items);
$cart_items_data = [];
while ($row = mysqli_fetch_assoc($result_cart_items)) {
    $cart_items_data[] = $row;
}

// Fetch order revenue data
$query_order_rev = "SELECT DATE(createdTime) AS order_date, SUM(o.grandTotal) AS revenue 
                    FROM orders o GROUP BY DATE(createdTime)";
$result_Order_rev = mysqli_query($conn, $query_order_rev);
$order_data_revenue = [];
while ($row = mysqli_fetch_assoc($result_Order_rev)) {
    $order_data_revenue[] = $row;
}


// Fetch daily revenue by main categories from the cart table
$query_daily_revenue_categories = "
    SELECT 
        cart.productCatogory, 
        SUM(cart.total) AS daily_revenue, 
        DATE(cart.createdAt) AS revenue_date
    FROM 
        cart
    GROUP BY 
        cart.productCatogory, DATE(cart.createdAt)
";

// Execute the query
$result_daily_revenue_categories = mysqli_query($conn, $query_daily_revenue_categories);

// Check if the query was successful
if (!$result_daily_revenue_categories) {
    // If the query failed, display the error
    die("Query failed: " . mysqli_error($conn));
}

$daily_revenue_categories_data = [];
while ($row = mysqli_fetch_assoc($result_daily_revenue_categories)) {
    $daily_revenue_categories_data[] = $row;
}

// Fetch today's total revenue
$query_today_revenue = " SELECT SUM(grandTotal) AS today_revenue  FROM orders WHERE DATE(createdTime) = CURDATE()"; // Fetch orders from today's date
$result_today_revenue = mysqli_query($conn, $query_today_revenue);
$today_revenue = 0;

if ($result_today_revenue && mysqli_num_rows($result_today_revenue) > 0) {
    $row = mysqli_fetch_assoc($result_today_revenue);
    $today_revenue = $row['today_revenue'];
}


// Fetch total customers and sellers
$query_customers = "SELECT COUNT(*) AS total_customers FROM customer_personal";
$result_customers = mysqli_query($conn, $query_customers);
$total_customers = mysqli_fetch_assoc($result_customers)['total_customers'];

$query_sellers = "SELECT COUNT(*) AS total_sellers FROM seller_personal";
$result_sellers = mysqli_query($conn, $query_sellers);
$total_sellers = mysqli_fetch_assoc($result_sellers)['total_sellers'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.css" />
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
    </style>

    </style>
</head>
<body>

    <h1>Admin Dashboard</h1>

    <!-- Top Summary Section -->
    <div class="top-summary">
        
    <div class="summary-card">
        <h2>LKR <?php echo number_format($today_revenue, 2); ?></h2> <!-- Display today's total revenue -->
        <p>Today's Total Revenue</p>
    </div>
        <div class="summary-card">
            <h2><?php echo $total_customers; ?></h2>
            <p>Total Customers</p>
        </div>

        <div class="summary-card">
            <h2><?php echo $total_sellers; ?></h2>
            <p>Total Sellers</p>
        </div>
        <div class="summary-card">
            <h2><?php echo array_sum(array_column($orders_data, 'daily_order_count')); ?></h2>
            <p>Total Orders</p>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <a href="orderRevenueTable.php" class="card-link">
            <div class="card">
            <h2>Revenue by Order Date</h2>
                <div style="width: 80%; margin: auto;" class="chart-container">
                    <canvas id="orderRevenueChart"></canvas>
                </div>
            </div>
        </a>

        <div class="card">
            <h2>Daily Order Count</h2>
            <div class="chart-container">
                <canvas id="orderCountChart"></canvas>
            </div>
        </div>

        
        <div class="card">
            <h2>Revenue by Store</h2>
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="card">
            <h2>Customer Registration Progress</h2>
            <div class="chart-container">
                <canvas id="customerProgressChart"></canvas>
            </div>
        </div>
        <div class="card">
            <h2>Daily Revenue by Main Categories</h2>
            <div class="chart-container">
                <canvas id="dailyRevenueCategoriesChart"></canvas>
            </div>
        </div>
       
        <div class="card">
            <h2>Daily Cart Items</h2>
            <div class="chart-container">
                <canvas id="cartItemsChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    // Order Count Chart
    const orderCountCtx = document.getElementById('orderCountChart').getContext('2d');
    const orderCountChart = new Chart(orderCountCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_column($orders_data, 'order_date')); ?>,
            datasets: [{
                label: 'Daily Orders',
                data: <?php echo json_encode(array_column($orders_data, 'daily_order_count')); ?>,
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

    // Revenue by Store Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($revenue_data, 'store_name')); ?>,
            datasets: [{
                label: 'Revenue',
                data: <?php echo json_encode(array_column($revenue_data, 'revenue')); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
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

    // Customer Registration Progress Chart
    const customerProgressCtx = document.getElementById('customerProgressChart').getContext('2d');
    const customerProgressChart = new Chart(customerProgressCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_column($customer_progress_data, 'reg_date')); ?>,
            datasets: [{
                label: 'Customer Registrations',
                data: <?php echo json_encode(array_column($customer_progress_data, 'customer_count')); ?>,
                backgroundColor: 'rgba(255, 206, 86, 0.3)',
                borderColor: 'rgba(255, 206, 86, 1)',
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

    // Daily Cart Items Chart
    const cartItemsCtx = document.getElementById('cartItemsChart').getContext('2d');
    const cartItemsChart = new Chart(cartItemsCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($cart_items_data, 'cart_date')); ?>,
            datasets: [{
                label: 'Daily Cart Items',
                data: <?php echo json_encode(array_column($cart_items_data, 'daily_cart_count')); ?>,
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

    // Daily Revenue by Main Categories Chart
    const dailyRevenueCategoriesCtx = document.getElementById('dailyRevenueCategoriesChart').getContext('2d');
    const dailyRevenueCategoriesChart = new Chart(dailyRevenueCategoriesCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($daily_revenue_categories_data, 'revenue_date')); ?>,
            datasets: [{
                label: 'Revenue',
                data: <?php echo json_encode(array_column($daily_revenue_categories_data, 'daily_revenue')); ?>,
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

    // Revenue by Order Date Chart (renamed to avoid conflict with store revenue chart)
    const orderRevenueCtx = document.getElementById('orderRevenueChart').getContext('2d');
    const orderRevenueChart = new Chart(orderRevenueCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($order_data_revenue, 'order_date')); ?>,
            datasets: [{
                label: 'Revenue',
                data: <?php echo json_encode(array_column($order_data_revenue, 'revenue')); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true
                },
                tooltip: {
                    enabled: true
                }
            }
        }
    });
</script>

        </script>
</body>
</html>


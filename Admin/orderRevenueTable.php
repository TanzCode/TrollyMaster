<?php
include 'conn.php';
// Fetch order revenue data
$query_order_rev = "SELECT DATE(createdTime) AS order_date, SUM(o.grandTotal) AS revenue 
                    FROM orders o 
                    GROUP BY DATE(createdTime)";
$result_Order_rev = mysqli_query($conn, $query_order_rev);

// Initialize array to store order revenue data
$order_data_revenue = [];

// Fetch results into the array
while ($row = mysqli_fetch_assoc($result_Order_rev)) {
    $order_data_revenue[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Revenue Data</title>
    <style>
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #81C408;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

    </style>
</head>
<body>

    <h2>Order Revenue Data with Progress</h2>

    <table>
        <thead>
            <tr style="background-color:#006A4E;">
                <th>Order Date</th>
                <th>Revenue (LKR)</th>
                <th>Progress Compared to Previous Day</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if there are any records to display
            if (!empty($order_data_revenue)) {
                $previous_revenue = null; // To store the previous day's revenue

                // Loop through the data and create table rows
                foreach ($order_data_revenue as $data) {
                    // Calculate progress compared to the previous day
                    if ($previous_revenue !== null) {
                        $revenue_change = $data['revenue'] - $previous_revenue;
                        $progress = ($revenue_change / $previous_revenue) * 100; // Percentage change
                    } else {
                        $progress = 0; // No previous data for the first row
                    }

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($data['order_date']) . "</td>";
                    echo "<td>" . number_format($data['revenue'], 2) . "</td>"; // Format revenue with 2 decimal points

                    // Display the progress with positive or negative sign
                    if ($progress == 0) {
                        echo "<td>No previous data</td>";
                    } else {
                        echo "<td>" . number_format($progress, 2) . "%</td>"; // Format progress percentage with 2 decimal points
                    }

                    echo "</tr>";

                    // Update previous_revenue for the next iteration
                    $previous_revenue = $data['revenue'];
                }
            } else {
                // If no data is available, show a message
                echo "<tr><td colspan='3'>No data available</td></tr>";
            }
            ?>
        </tbody>
    </table>

<!--     
        <h2>Revenue by Order Date</h2>
            <div style="width: 80%; margin: auto;" class="chart-container">
        
        <canvas id="orderRevenueChart"></canvas>
     -->

        
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>

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
<script>
   
</body>
</html>

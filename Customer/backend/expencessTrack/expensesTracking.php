<?php
session_start();
include '../dbConnection.php';

if (!isset($_SESSION['cusID'])) {
    header("Location: ../../login.html");
    exit();
}

$cusID = $_SESSION['cusID'];

// Fetch the latest budget data
$budgetQuery = "SELECT * FROM expenseshistory WHERE cusID = ? ORDER BY enddate DESC LIMIT 1";
$budgetStmt = $conn->prepare($budgetQuery);
$budgetStmt->bind_param("i", $cusID);
$budgetStmt->execute();
$budgetResult = $budgetStmt->get_result()->fetch_assoc();

$currentDate = date("Y-m-d");

// Check if the current date is greater than the end date of the budget period
if ($currentDate > $budgetResult['enddate']) {
    // Calculate the duration of the previous period
    $startDate = new DateTime($budgetResult['startdate']);
    $endDate = new DateTime($budgetResult['enddate']);
    $interval = $startDate->diff($endDate);
    $periodDays = $interval->days; // Get the number of days in the previous period

    // Calculate new start and end dates for the next period
    $newStartDate = date("Y-m-d", strtotime($budgetResult['enddate'] . ' +1 day'));
    $newEndDate = date("Y-m-d", strtotime($newStartDate . " +$periodDays days"));

    // Insert a new budget period into the expenses table
    $newBudgetQuery = "INSERT INTO expenses (budget, startdate, enddate, cusID, remainingBudget) 
                       VALUES (?, ?, ?, ?, ?)";
    $newBudgetStmt = $conn->prepare($newBudgetQuery);
    
    // Reset remaining budget to the original budget amount
    $remainingBudget = $budgetResult['budget'];

    // Bind parameters and execute the query
    $newBudgetStmt->bind_param("ssssi", $budgetResult['budget'], $newStartDate, $newEndDate, $cusID, $remainingBudget);
    $newBudgetStmt->execute();

    // Insert a new record into expenseshistory table
    $historyQuery = "INSERT INTO expenseshistory (budget, startdate, enddate, cusID, remainingBudget) 
                     VALUES (?, ?, ?, ?, ?)";
    $historyStmt = $conn->prepare($historyQuery);
    $historyStmt->bind_param("ssssi", $budgetResult['budget'], $newStartDate, $newEndDate, $cusID, $remainingBudget);
    $historyStmt->execute();

    // Assign values for later use
    $budget = $budgetResult['budget'];
    $remainingBudget = $budgetResult['remainingBudget'] ?? 0;
} else {
    // Fetch the most recent budget data from the expenseshistory table
    $budgetQuery = "SELECT budget, remainingBudget, updatedTime FROM expenseshistory 
                    WHERE cusID = ? ORDER BY updatedTime DESC LIMIT 1";
    $budgetStmt = $conn->prepare($budgetQuery);

    // Check if the statement was prepared correctly
    if (!$budgetStmt) {
        die("Error preparing statement for budget query: " . $conn->error);
    }

    // Bind the customer ID and execute the query
    $budgetStmt->bind_param("i", $cusID);
    $budgetStmt->execute();

    // Fetch the result and assign to variables
    $budgetResult = $budgetStmt->get_result()->fetch_assoc();
    $budget = $budgetResult['budget'] ?? 0;
    $remainingBudget = $budgetResult['remainingBudget'] ?? 0;
    $lastUpdate = isset($budgetResult['updatedTime']) ? date("M d, Y", strtotime($budgetResult['updatedTime'])) : 'Not set';
}


// Calculate spending percentage
$spentAmount = $budget - $remainingBudget;
$spendingPercentage = $budget > 0 ? round(($spentAmount / $budget) * 100) : 0;

// Fetch expense data
$expenseQuery = "SELECT createdAt, productCatogory, total, productName 
                 FROM cart WHERE cusID = ? AND status = 1 
                 ORDER BY createdAt DESC";
$expenseStmt = $conn->prepare($expenseQuery);
if (!$expenseStmt) {
    die("Error preparing statement for expense query: " . $conn->error);
}
$expenseStmt->bind_param("i", $cusID);
$expenseStmt->execute();
$expenseResults = $expenseStmt->get_result();

// Prepare category and monthly data
$categoryTotals = [];
$monthlyTotals = [];
while ($row = $expenseResults->fetch_assoc()) {
    $category = $row['productCatogory'];
    $total = $row['total'];
    $month = date('M Y', strtotime($row['createdAt']));
    
    $categoryTotals[$category] = ($categoryTotals[$category] ?? 0) + $total;
    $monthlyTotals[$month] = ($monthlyTotals[$month] ?? 0) + $total;
}

// Prepare JSON data for charts
$categoryLabels = json_encode(array_keys($categoryTotals));
$categoryData = json_encode(array_values($categoryTotals));
$monthlyLabels = json_encode(array_keys($monthlyTotals));
$monthlyData = json_encode(array_values($monthlyTotals));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Expense Tracker | Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/stylesExpensesTrack.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include 'header.php'; ?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1><i class="fas fa-chart-pie"></i> Expense Dashboard</h1>
        <p class="last-update">Last updated: </p><?php// echo $lastUpdate; ?>
    </div>
    <div style="text-align: center; margin: 20px;">
    <button onclick="window.location.href='expencessForm.php'" style="padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Set the budget</button>
</div>
    <div class="overview-cards">
        <div class="card budget-card">
            <div class="card-icon"><i class="fas fa-wallet"></i></div>
            <div class="card-content">
                <h3>Total Budget</h3>
                <p class="amount">LKR <?php echo number_format($budget, 2); ?></p>
            </div>
        </div>

        <div class="card remaining-card">
            <div class="card-icon"><i class="fas fa-piggy-bank"></i></div>
            <div class="card-content">
                <h3>Remaining Budget</h3>
                <p class="amount">LKR<?php echo number_format($remainingBudget, 2); ?></p>
            </div>
        </div>

        <div class="card spending-card">
            <div class="card-icon"><i class="fas fa-chart-line"></i></div>
            <div class="card-content">
                <h3>Spending Progress</h3>
                <div class="progress-bar">
                    <div class="progress" style="width: <?php echo $spendingPercentage; ?>%">
                        <span><?php echo $spendingPercentage; ?>%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="chart-grid">
        <div class="chart-card">
            <h3><i class="fas fa-chart-pie"></i> Category Breakdown</h3>
            <canvas id="categoryChart"></canvas>
        </div>

        <div class="chart-card">
            <h3><i class="fas fa-chart-line"></i> Monthly Spending Trend</h3>
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <div class="transactions-section">
        <h3><i class="fas fa-receipt"></i> Recent Transactions</h3>
        <div class="table-responsive">
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $expenseStmt->execute();
                    $expenseResults = $expenseStmt->get_result();
                    while ($row = $expenseResults->fetch_assoc()) {
                        echo "<tr>
                                <td>" . date("M d, Y", strtotime($row['createdAt'])) . "</td>
                                <td><span class='category-badge'>{$row['productCatogory']}</span></td>
                                <td>{$row['productName']}</td>
                                <td class='amount'>LKR" . number_format($row['total'], 2) . "</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Category breakdown chart
const ctxCategory = document.getElementById('categoryChart').getContext('2d');
new Chart(ctxCategory, {
    type: 'doughnut',
    data: {
        labels: <?php echo $categoryLabels; ?>,
        datasets: [{
            data: <?php echo $categoryData; ?>,
            backgroundColor: [
                '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEEAD', '#D4A5A5'
            ],
            borderWidth: 2
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    padding: 20,
                    font: {
                        size: 12
                    }
                }
            }
        },
        cutout: '65%',
        responsive: true,
        maintainAspectRatio: false
    }
});

// Monthly trend chart with improved clarity
const ctxTrend = document.getElementById('trendChart').getContext('2d');
new Chart(ctxTrend, {
    type: 'line',
    data: {
        labels: <?php echo $monthlyLabels; ?>,
        datasets: [{
            label: 'Monthly Spending',
            data: <?php echo $monthlyData; ?>,
            borderColor: '#FF6384',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderWidth: 3,
            pointBackgroundColor: '#FF6384',
            pointBorderColor: '#fff',
            pointHoverRadius: 6,
            tension: 0.3 // Slightly smoothens the line
        }]
    },
    options: {
        plugins: {
            legend: {
                display: true,
                labels: {
                    color: '#333'
                }
            },
            tooltip: {
                enabled: true,
                backgroundColor: '#fff',
                bodyColor: '#000',
                borderColor: '#FF6384',
                borderWidth: 1
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#E5E5E5',
                    drawBorder: false
                },
                ticks: {
                    callback: function(value) {
                        return 'LKR' + value.toLocaleString();
                    }
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        },
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>

<?php
$budgetStmt->close();
$expenseStmt->close();
$conn->close();
?>

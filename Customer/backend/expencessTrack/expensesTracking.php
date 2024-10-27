<?php
include '../dbConnection.php'; // Include database connection

// Assuming `cusID` is obtained from session for the logged-in user
$cusID = 22; // Replace with session variable if needed

// Fetch budget and remaining budget
$budgetQuery = "SELECT budget, remainingBudget FROM expenseshistory 
                WHERE cusID = ? ORDER BY updatedTime DESC LIMIT 1";
$budgetStmt = $conn->prepare($budgetQuery);

if (!$budgetStmt) {
    // Display error if prepare failed
    die("Error preparing statement for budget query: " . $conn->error);
}

$budgetStmt->bind_param("i", $cusID);
$budgetStmt->execute();
$budgetResult = $budgetStmt->get_result()->fetch_assoc();

$budget = $budgetResult['budget'] ?? 0;
$remainingBudget = $budgetResult['remainingBudget'] ?? 0;

// Fetch expense details from cart items
$expenseQuery = "SELECT createdAt, productCatogory, total, productName 
                 FROM cart WHERE cusID = ? AND status = 1";
$expenseStmt = $conn->prepare($expenseQuery);

if (!$expenseStmt) {
    // Display error if prepare failed
    die("Error preparing statement for expense query: " . $conn->error);
}

$expenseStmt->bind_param("i", $cusID);
$expenseStmt->execute();
$expenseResults = $expenseStmt->get_result();

// Remaining code for handling and displaying data...

// Prepare category breakdown for Chart.js
$categoryTotals = [];
while ($row = $expenseResults->fetch_assoc()) {
    $category = $row['productCatogory'];
    $total = $row['total'];

    if (!isset($categoryTotals[$category])) {
        $categoryTotals[$category] = 0;
    }
    $categoryTotals[$category] += $total;
}

// Convert data to JavaScript format
$categoryLabels = json_encode(array_keys($categoryTotals));
$categoryData = json_encode(array_values($categoryTotals));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Luxurious expenses tracking page to monitor and manage your grocery spending.">
    <title>Grocery Expenses Tracking | Luxurious Design</title>
    <link rel="stylesheet" href="../css/stylesExpensesTrack.css">
    <!-- Linking Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container expense-tracker" style="margin-top:190px;">
    <header class="header">
        <h1 class="text-primary">Track Your Grocery Expenses</h1>
    </header>

    <!-- Summary Section -->
    <section class="summary-cards">
        <div class="card">
            <h3>Total Budget</h3>
            <p>$<?php echo number_format($budget, 2); ?></p>
        </div>
        <div class="card">
            <h3>Budget Remaining</h3>
            <p>$<?php echo number_format($remainingBudget, 2); ?></p>
        </div>
        <div class="card">
            <h3>Grocery Expenses Breakdown</h3>
            <canvas id="expenseChart"></canvas>
        </div>
    </section>

    <!-- Expense Details Section -->
    <section class="expense-details">
        <h2>Expense History</h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reset the result pointer and fetch again for table display
                $expenseStmt->execute();
                $expenseResults = $expenseStmt->get_result();
                while ($row = $expenseResults->fetch_assoc()) {
                    echo "<tr>
                            <td>" . date("Y-m-d", strtotime($row['createdAt'])) . "</td>
                            <td>{$row['productCatogory']}</td>
                            <td>$" . number_format($row['total'], 2) . "</td>
                            <td>{$row['productName']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</div>

<!-- Script for initializing the expense chart with grocery categories -->
<script>
    const ctx = document.getElementById('expenseChart').getContext('2d');
    const expenseChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?php echo $categoryLabels; ?>,
            datasets: [{
                label: 'Grocery Categories Breakdown',
                data: <?php echo $categoryData; ?>,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>
</body>
</html>

<?php
$budgetStmt->close();
$expenseStmt->close();
$conn->close();
?>

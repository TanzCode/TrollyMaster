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
<?php
include 'header.php';
?>
    <div class="container expense-tracker" style="margin-top:90px;">
        <header class="header">
            <h1 class="text-primary">Track Your Grocery Expenses</h1>
        </header>

        <!-- Summary Section -->
        <section class="summary-cards">
            <div class="card">
                <h3>Total Spent</h3>
                <p>$2,500</p>
            </div>
            <div class="card">
                <h3>Budget Remaining</h3>
                <p>$1,500</p>
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
                    <tr>
                        <td>2024-10-01</td>
                        <td>Fruits</td>
                        <td>$200</td>
                        <td>Fresh apples, bananas, oranges</td>
                    </tr>
                    <tr>
                        <td>2024-10-02</td>
                        <td>Vegetables</td>
                        <td>$150</td>
                        <td>Spinach, broccoli, carrots</td>
                    </tr>
                    <tr>
                        <td>2024-10-03</td>
                        <td>Dairy</td>
                        <td>$100</td>
                        <td>Milk, cheese, yogurt</td>
                    </tr>
                    <tr>
                        <td>2024-10-05</td>
                        <td>Meat</td>
                        <td>$250</td>
                        <td>Chicken, beef, pork</td>
                    </tr>
                    <!-- More rows can be added -->
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
                labels: ['Fruits', 'Vegetables', 'Dairy', 'Meat', 'Bakery', 'Beverages'],
                datasets: [{
                    label: 'Grocery Categories Breakdown',
                    data: [200, 150, 100, 250, 120, 80], // Mock data for each category
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

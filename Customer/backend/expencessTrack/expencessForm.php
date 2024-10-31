<?php
session_start();
require '../dbConnection.php';

// Initialize error or success messages
$message = '';

// Check if the customer ID is stored in the session
if (!isset($_SESSION['cusID'])) {
    // Redirect to login page if customer is not logged in
    header("Location: login.php");
    exit();
}

// Get customer ID from session
$customerID = $_SESSION['cusID'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data from the POST request
    $cusID = $customerID;
    $totalBudget = mysqli_real_escape_string($conn, $_POST['total_budget']);
    $startDate = mysqli_real_escape_string($conn, $_POST['start_date']);
    $endDate = mysqli_real_escape_string($conn, $_POST['end_date']);

    // Check if all the fields are filled
    if (empty($cusID) || empty($totalBudget) || empty($startDate) || empty($endDate)) {
        $message = "All fields are required.";
    } else {
        // Begin database transaction
        mysqli_begin_transaction($conn);

        try {
            // Check if a budget already exists for this customer
            $checkQuery = "SELECT * FROM expenses WHERE cusID = '$cusID' LIMIT 1";
            $checkResult = mysqli_query($conn, $checkQuery);

            if ($checkResult && mysqli_num_rows($checkResult) > 0) {
                // A budget already exists, so update it
                $row = mysqli_fetch_assoc($checkResult);
                $budgetID = $row['budgetID'];

                // Update the existing budget
                $updateQuery = "UPDATE expenses 
                                SET budget = '$totalBudget', startdate = '$startDate', enddate = '$endDate', remainingBudget = '$totalBudget' 
                                WHERE budgetID = '$budgetID'";

                if (!mysqli_query($conn, $updateQuery)) {
                    throw new Exception("Error updating budget: " . mysqli_error($conn));
                }
                $message = "Budget updated successfully!";
            } else {
                // No existing budget found, insert a new one
                $insertQuery = "INSERT INTO expenses (budget, startdate, enddate, cusID, remainingBudget) 
                                VALUES ('$totalBudget', '$startDate', '$endDate', '$cusID', '$totalBudget')";

                if (!mysqli_query($conn, $insertQuery)) {
                    throw new Exception("Error inserting new budget: " . mysqli_error($conn));
                }
                $message = "Budget set successfully!";
            }

            // Commit the transaction
            mysqli_commit($conn);

        } catch (Exception $e) {
            // Rollback transaction on error
            mysqli_rollback($conn);
            $message = $e->getMessage();
        }
    }
    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Luxurious expenses tracking page to monitor and manage your grocery spending.">
    <title>Grocery Expenses Tracking | Luxurious Design</title>
    <link rel="stylesheet" href="../css/stylesExpensesTrack.css">
    <style>
        /* General form styling */
        .budget-form {
            max-width: 500px;
            margin: 0 auto;
            padding: 40px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-family: 'Poppins', sans-serif;
        }

        .budget-form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .budget-form input, .budget-form select, .budget-form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .budget-form input:focus, .budget-form select:focus {
            border-color: #66afe9;
            outline: none;
            box-shadow: 0 0 5px rgba(102, 175, 233, 0.5);
        }

        .budget-form button {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .budget-form button:hover {
            background-color: #45a049;
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h2 {
            color: #4CAF50;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* Message styling */
        .message {
            text-align: center;
            font-weight: bold;
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<!-- Stylish Form -->
<div class="budget-form" style="margin-top:200px;">
    <div class="form-header">
        <h2>Set Your Budget</h2>
    </div>

    <?php if (!empty($message)): ?>
        <p class="message <?= strpos($message, 'Error') !== false ? 'error' : '' ?>"><?= $message ?></p>
    <?php endif; ?>
    
    <form method="POST" action="">
    <!--<label for="customer_id">Customer ID:</label>-->
    <!--<input type="text" id="customer_id" name="customer_id" placeholder="Enter your ID" required>
        --> 
        <label for="total_budget">Total Budget:</label>
        <input type="number" id="total_budget" name="total_budget" placeholder="Enter budget amount" required>
        
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required>
        
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" required>
        
        <button type="submit">Set Budget</button>
        <div style="text-align: center; margin-top: 20px;">
    <button onclick="window.location.href='expensesTracking.php'" style="padding: 10px 20px; font-size: 16px; background-color: #d9534f; color: white; border: none; border-radius: 5px; cursor: pointer;">Back to Expenses Tracker</button>
</div>
    </form>
</div>

</body>
</html>

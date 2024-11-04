<?php
session_start();
require '../dbConnection.php';

// Check if the customer is logged in
if (!isset($_SESSION['cusID'])) {
    header('Location: ../login.php'); // Redirect to login page if customer is not logged in
    exit();
}

$cusID = $_SESSION['cusID']; // Retrieve customer ID from session

// Check if grandTotal is available in session
if (isset($_SESSION['grandTotal'])) {
    $grandTotal = $_SESSION['grandTotal'];

    // Proceed if both cusID and grandTotal are available
    if ($cusID && $grandTotal) {
        // Start database transaction
        mysqli_begin_transaction($conn);

        try {
            // Step 1: Insert the order into the `orders` table
            $orderQuery = "INSERT INTO orders (cusID, grandTotal, orderStatus, createdTime, paymentStatus) 
                           VALUES ('$cusID', '$grandTotal', 'Processing', NOW(), 'Paid')";

            include '../ordernotification.php';
            if (!mysqli_query($conn, $orderQuery)) {
                throw new Exception("Error placing order: " . mysqli_error($conn));
            }

            // Get the last inserted order ID
            $orderID = mysqli_insert_id($conn);

            // Step 2: Update the `cart` table - mark purchased items (status 0 to 1)
            $updateCartQuery = "UPDATE cart SET status = 1, orderID='$orderID', orderStatus='Processing' WHERE cusID = '$cusID' AND status = 0";
            if (!mysqli_query($conn, $updateCartQuery)) {
                throw new Exception("Error updating cart: " . mysqli_error($conn));
            }

            // Step 3: Update the `expenses` table by reducing the customer's remaining budget
            // Fetch the customer's active budget
            $budgetQuery = "SELECT * FROM expenses WHERE cusID = '$cusID' AND startdate <= NOW() AND enddate >= NOW() LIMIT 1";
            $budgetResult = mysqli_query($conn, $budgetQuery);

            if ($budgetResult && mysqli_num_rows($budgetResult) > 0) {
                $budgetRow = mysqli_fetch_assoc($budgetResult);
                $remainingBudget = $budgetRow['remainingBudget'];
                $budgetID = $budgetRow['budgetID'];

                // Deduct the grandTotal from the remaining budget, allowing negative results
                $newRemainingBudget = $remainingBudget - $grandTotal;
                $updateBudgetQuery = "UPDATE expenses SET remainingBudget = '$newRemainingBudget' WHERE budgetID = '$budgetID'";
                if (!mysqli_query($conn, $updateBudgetQuery)) {
                    throw new Exception("Error updating budget: " . mysqli_error($conn));
                }

                // Step 4: Insert the expense history into `expenseshistory`
                $insertHistoryQuery = "INSERT INTO expenseshistory (budgetID, budget, startdate, enddate, cusID, remainingBudget, updatedTime) 
                                       VALUES ('$budgetID', '{$budgetRow['budget']}', '{$budgetRow['startdate']}', '{$budgetRow['enddate']}', '$cusID', '$newRemainingBudget', NOW())";
                if (!mysqli_query($conn, $insertHistoryQuery)) {
                    throw new Exception("Error inserting expense history: " . mysqli_error($conn));
                }
            } else {
                throw new Exception("No active budget found for the customer.");
            }

            // Commit the transaction
            mysqli_commit($conn);
            echo "Order placed successfully!";

        } catch (Exception $e) {
            // Rollback transaction in case of errors
            mysqli_rollback($conn);
            echo $e->getMessage();
        }
    } else {
        echo "Missing order information.";
    }
} else {
    echo "Payment information is missing.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link rel="stylesheet" href="Sass/success.min.css">
</head>
<body>
    <div class="conn">
        <div class="container">
            <div class="header">
                <img src="https://img.icons8.com/?size=100&id=a4l6bA9mSmBh&format=png&color=40C057" alt="Checkmark" class="checkmark">
                
            </div>
            <h1>Thank You!</h1>
            <p>Your order has been placed successfully.</p>
            <p>Your Order ID is: <strong><?php echo $orderID; ?></strong></p>
            <p>You will receive an email and SMS shortly.</p>
            
            <button class="backbtn"><a href="../index.php" class="btn btn-primary">Continue Shopping</a></button>
        </div>
    </div>
</body>
</html>

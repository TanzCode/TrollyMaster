<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


session_start();
require '../dbConnection.php';




// Email sending function
function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'trollymaster.lk@gmail.com';      
        $mail->Password   = 'wvlh qfbg xnas zukm';                   
        $mail->SMTPSecure = 'tls';                                  
        $mail->Port       = 587;                                    

        //Recipients
        $mail->setFrom('trollymaster.lk@gmail.com', 'Trolly Master');
        $mail->addAddress($to);                                      

        // Content
        $mail->isHTML(true);                                       
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);                         
        $mail->send();
        echo 'Email has been sent successfully!';
    } catch (Exception $e) {
       // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
// Check if the customer is logged in
if (!isset($_SESSION['cusID'])) {
    header('Location: ../login.php'); // Redirect to login page if customer is not logged in
    exit();
}


    $cusID = $_SESSION['cusID']; // Retrieve customer ID from session

    // Fetch customer details such as first name, last name, and email
    $customerQuery = "SELECT fName, lName, email FROM customer_personal WHERE cusID = '$cusID'";
    $customerResult = mysqli_query($conn, $customerQuery);

    if ($customerResult && mysqli_num_rows($customerResult) > 0) {
        $customerRow = mysqli_fetch_assoc($customerResult);
        $firstName = $customerRow['fName'];
        $lastName = $customerRow['lName'];
        $email = $customerRow['email'];
    } else {
      //  echo "Customer details not found.";
        exit();
    }

 

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
                $initialBudget = $budgetRow['budget'];
                $budgetID = $budgetRow['budgetID'];

                // Deduct the grandTotal from the remaining budget, allowing negative results
                $newRemainingBudget = $remainingBudget - $grandTotal;

             // Check if remaining budget is below 5% of initial budget
            $threshold = $initialBudget * 0.05; // 5% of the initial budget
            if ($newRemainingBudget < $threshold) {
                // Prepare notification for the customer
                $message = "Dear $firstName $lastName,\nYour remaining budget has dropped below 5%. Your current remaining budget is $newRemainingBudget. Please manage your budget wisely.\nThank you for using Trolly Master.";

                // Insert notification into the notifications table for the customer
                $notificationQuery = "INSERT INTO notifications (recipientType, recipientID, Message, status, timeStamp) 
                    VALUES ('customer', $cusID, '$message', 0, NOW())";
                mysqli_query($conn, $notificationQuery);
            }



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
            //echo "Order placed successfully!";

             // Send the confirmation email to the customer
             $subject = "Order Confirmation from Trolly Master";
             $body = "
                 <html>
                 <body>
                 <p>Dear $firstName $lastName,</p>
                 <p>Thank you for shopping with Trolly Master! Your order has been placed successfully and is being processed. You can expect your delivery to arrive in 7-8 business days.</p>
                 
                 <h3>Order Details:</h3>
                 <p><strong>Order ID:</strong> $orderID</p>
                 <p><strong>Grand Total:</strong> $grandTotal</p>
                 <p><strong>Estimated Delivery Time:</strong> 7-8 business days</p>
 
                 <p>Thank you for choosing Trolly Master. We look forward to serving you again!</p>
                 <p>If you have any questions about your order, feel free to contact us at <a href='mailto:trollymaster.lk@gmail.com'>trollymaster.lk@gmail.com</a> or call +94 123 456 789.</p>
 
                 <p>Best regards,<br>The Trolly Master Team</p>
                 </body>
                 </html>
             ";
 
             sendEmail($email, $subject, $body);
 

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

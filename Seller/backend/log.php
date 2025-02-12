<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Database connection
    include 'dbConnection.php';

    // Get the input data 
    $email =  $_POST["email"];
    $password = $_POST["password"];

   
    if (!empty($email) && !empty($password)) {

        // SQL query to check if the user exists
        $sql = "SELECT * FROM seller_personal WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        

        if ($result && mysqli_num_rows($result) > 0) 
        {
            // Fetch the user's data
            $row = mysqli_fetch_assoc($result);
            $hash = $row['password'];

            // Verify the password
            if (password_verify($password, $hash)) {
                // Start the session and set session variables
                
                session_start();
                $_SESSION['sellerLoggedin'] = true;
                $_SESSION['sellerID'] = $row['sellerID'];
                $_SESSION['fName'] = $row['fName'];
                $_SESSION['lName'] = $row['lName'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['phone'] = $row['phone'];

                 // Query to get the store ID and store name based on sellerID
                 $storeQuery = "SELECT * FROM store WHERE sellerID = '{$row['sellerID']}'";
                 $storeResult = mysqli_query($conn, $storeQuery);
 
                 if ($storeResult && mysqli_num_rows($storeResult) > 0) {
                     $storeRow = mysqli_fetch_assoc($storeResult);
                     $_SESSION['storeID'] = $storeRow['storeID']; 
                     $_SESSION['storeName'] = $storeRow['storeName']; 
                 } else {
                     echo "<h3>Store information not found.</h3>";
                     header("Location: SuccessError/error.html");
                     exit();
                 }
 


                echo "<h3>Login successful! Welcome, " . $_SESSION['storeName'] . ".</h3>";
                // Redirect to the passenger dashboard or another page
                 header("Location: home.php");
                exit();
            } 
            else 
            {
                echo "<h3>Incorrect password. Please try again.</h3>";
                // Redirect to the passenger dashboard or another page
                header("Location: SuccessError/error.html");
            }
        } 
        else 
        {
            echo "<h3>No account found with that email. Please sign up first.</h3>";
            // Redirect to the passenger dashboard or another page
            header("Location: SuccessError/error.html");
        }
        
        // Close the database connection
        mysqli_close($conn);
    } else 
    {
        echo "<h3>Please fill in both fields.</h3>";
    }
}

?>


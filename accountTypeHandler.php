<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["accountType"])) {
        $accountType = $_POST["accountType"];
        
        // Store the account type in a session variable
        $_SESSION["accountType"] = $accountType;

        // Redirect to the appropriate login page based on the account type
        if ($accountType == "customer") {
            header("Location: Customer/login.html");
            exit();
        } else if ($accountType == "Seller") {
            header("Location: Seller/login.html");
            exit();
        } else {
            echo "Invalid account type selected.";
        }
    } else {
        echo "No account type selected.";
    }
} else {
    echo "Invalid request method.";
}
?>

<?php
session_start();
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Hard-coded credentials
    $admin_username = "admin@trollymaster.com";
    $admin_password = "1234";

    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the credentials match
    if ($email === $admin_username && $password === $admin_password) {
        // Success message or redirect to admin dashboard
        echo "Login successful. Welcome, Admin!";
        $_SESSION['adminlogin'] = true;
         header("Location: dashboard.php");
        exit();
    } else {
        // Error message
        echo "Login failed. Invalid username or password.";
    }
}
?>

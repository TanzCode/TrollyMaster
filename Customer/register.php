
<?php 
session_start();
include('conn.php');?>
<?php
    // Retrieve form data
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $phone = $_POST['phone'];
    $streetAddress = $_POST['street-address'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $postalCode = $_POST['postal-code'];

    // Basic form validation
    $errors = [];

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    //customer registration input query
    $sql = "INSERT INTO customer_personal (fName, lName, gender, email, password, phone, streetAddress, city, province, postalCode) VALUES ('$firstName', '$lastName', '$gender', '$email', '$password', '$phone', '$streetAddress', '$city', '$province', '$postalCode')";


    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
        header("Location: login.html");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
?>

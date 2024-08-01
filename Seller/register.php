<?php 
session_start();
include('conn.php');

// seller personal info
$firstName = $_POST['first-name'];
$lastName = $_POST['last-name'];
$nic = $_POST['nic'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirm-password'];
$phone = $_POST['phone'];
$perAddress = $_POST['perAddress'];

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

// Check if email already exists
$emailCheckQuery = "SELECT email FROM seller_personal WHERE email = '$email'";
$emailCheckResult = $conn->query($emailCheckQuery);

if ($emailCheckResult->num_rows > 0) {
    $errors[] = "Email already exists.";
}

// If there are no errors, proceed with the insertion
if (empty($errors)) {
    // seller registration input query
    $sql = "INSERT INTO seller_personal (fName, lName, gender, email, password, phone, perAddress) 
            VALUES ('$firstName', '$lastName', '$gender', '$email', '$password', '$phone', '$perAddress')";


if ($conn->query($sql) === TRUE) {
    include 'shopReg.html';
     
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
} else {
// Display errors
foreach ($errors as $error) {
    echo "<div class='error-message'>$error</div>";
}
}

$conn->close();
?>

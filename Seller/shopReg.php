<?php 
session_start();
include('conn.php');

// Collect and sanitize input data

$store_name = $_POST['name'];
$storeRegID =$_POST['regID'];
$streetAddress =$_POST['street-address'];
$city = $_POST['city'];
$province = $_POST['province'];
$postalCode = $_POST['postal-code'];

// Shop registration input query
$sql = "INSERT INTO store (regID, name, streetAddress, city, province, postalCode) 
        VALUES ('$storeRegID','$store_name','$streetAddress', '$city','$province','$postalCode')";

if ($conn->query($sql) === TRUE) {
    echo "Registration successful!";
    header("Location: login.html.");
    exit();

    
    exit();
} else {
    echo "Error:" . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
<?php
include 'dbConnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sellerID = $_POST['sellerID'];
    $fName = $_POST['fName'];
    $lName = $_POST['lName'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $perAddress = $_POST['perAddress'];
    $regID = $_POST['regID'];
    $name = $_POST['name'];
    $streetAddress = $_POST['streetAddress'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $postalCode = $_POST['postalCode'];
    $logo = $_POST['logo'];

    // Update seller_personal table
    $updateSeller = "UPDATE seller_personal SET fName=?, lName=?, gender=?, email=?, password=?, phone=?, perAddress=? WHERE sellerID=?";
    $stmt1 = $conn->prepare($updateSeller);
    $stmt1->bind_param("sssssssi", $fName, $lName, $gender, $email, $password, $phone, $perAddress, $sellerID);
    $stmt1->execute();

    // Update store table
    $updateStore = "UPDATE store SET name=?, streetAddress=?, city=?, province=?, postalCode=?, logo=? WHERE regID=?";
    $stmt2 = $conn->prepare($updateStore);
    $stmt2->bind_param("ssssiis", $name, $streetAddress, $city, $province, $postalCode, $logo, $regID);
    $stmt2->execute();

    if ($stmt1 && $stmt2) {
        echo "Records updated successfully.";
    } else {
        echo "Error updating records: " . $conn->error;
    }
    
    $stmt1->close();
    $stmt2->close();
    $conn->close();
}
?>

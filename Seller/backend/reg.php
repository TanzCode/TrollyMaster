<?php
// Include database connection
include 'dbConnection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get data from Personal Details form (Step 2)
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $gender = $_POST['gender'];
    $perNumber = $_POST['perNumber'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confPassword = $_POST['confPassword'];

    // Get data from Shop Details (Step 1) passed through hidden fields
    $regID = $_POST['regID'];
    $storeName = $_POST['storeName'];
    $streetAddress = $_POST['streetAddress'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $postalCode = $_POST['postalCode'];

    // Validate that passwords match
    if ($password !== $confPassword) {
        header("Location: SuccessError/error.html");
        exit();
    }

    // Hash the password for security
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert Personal Details into the database
    $sqlPersonal = "INSERT INTO seller_personal (fName, lName, gender, phone, perAddress, email, password) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmtPersonal = $conn->prepare($sqlPersonal);
    $stmtPersonal->bind_param("sssssss", $firstName, $lastName, $gender, $perNumber, $address, $email, $hashPassword);

    // Execute the seller_personal insertion
    if ($stmtPersonal->execute()) {
        // Get the last inserted ID (seller_personal ID)
        $sellerID = $conn->insert_id;

        // Insert Shop Details into the store table, including the seller_personal ID
        $sqlShop = "INSERT INTO store (regID, name, streetAddress, city, province, postalCode,sellerID) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtShop = $conn->prepare($sqlShop);
        $stmtShop->bind_param("ssssssi", $regID, $storeName, $streetAddress, $city, $province, $postalCode, $sellerID);

        // Execute the store insertion
        if ($stmtShop->execute()) {
            echo "Registration successful!";
            header("Location: SuccessError/success.html");
        } else {
            echo "Error: " . $stmtShop->error;
            header("Location: SuccessError/error.html");
        }

        // Close the Shop statement
        $stmtShop->close();
    } else {
        echo "Error: " . $stmtPersonal->error;
        header("Location: SuccessError/error.html");
    }

    // Close the Personal statement and connection
    $stmtPersonal->close();
    $conn->close();
} else {
    echo "Invalid request method!";
    header("Location: SuccessError/error.html");
}
?>

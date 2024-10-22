<?php

session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include 'dbConnection.php';


    $productID = $_POST['productID'];
    $productName = $_POST['productName'];
    $category = $_POST['category'];
    $subCategory = $_POST['subCategory'];
    $description = $_POST['description'];
    $brand = $_POST['brand'];
    $storageRequirements = $_POST['storageRequirements'];
    $discounts = $_POST['discounts'];
    $specialDetails = $_POST['specialDetails'];
    $stockAmount = $_POST['stockAmount'];
    $price = $_POST['price'];


    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target_dir = "upload";
        $target_file = $target_dir . basename($image);
        

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {

            $sql = "UPDATE product SET productName=?, category=?, subCategory=?, description=?, brand=?, storageRequirements=?, discounts=?, specialDetails=?, stockAmount=?, price=?, image=? WHERE productID=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssiiisi", $productName, $category, $subCategory, $description, $brand, $storageRequirements, $discounts, $specialDetails, $stockAmount, $price, $target_file, $productID);
        } else {
            exit("Error uploading the file.");
        }
    } else {
        $sql = "UPDATE product 
        SET productName=?, category=?, subCategory=?, description=?, brand=?, storageRequirements=?, discounts=?, specialDetails=?, stockAmount=?, price=? 
        WHERE productID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssiiii", $productName, $category, $subCategory, $description, $brand, $storageRequirements, $discounts, $specialDetails, $stockAmount, $price, $productID);
        }


    if ($stmt->execute()) {
        echo "Product updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }


    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>

<?php
// Start the session
session_start();

// Include the database connection
include('dbConnection.php');

// Check if the seller ID is set in the session
if (isset($_SESSION['sellerID'])) {
    $sellerID = $_SESSION['sellerID'];
} else {
    echo "Seller ID not set in the session.";
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

// Retrieve form data
$storeID = $_POST['storeID'];
$productName = $_POST['productName'];
$category = $_POST['category'];
$subCategory = $_POST['subcategory'];
$description = $_POST['description'];
$price = $_POST['price'];
$brand = $_POST['brand'];
$storageRequirements = $_POST['storage_requirements'];
$discounts = $_POST['discounts'];
$specialDetails = $_POST['special_details'];
$stockAmount = $_POST['stock_amount'];
$qty = $_POST['qty'];
$scale = $_POST['scale'];



//unit price 
if($scale=="l")
{
    $unitPrice = $price/($qty*1000);
}
else if($scale=="ml")
{
    $unitPrice = $price/($qty);
}
else if($scale=="kg")
{
    $unitPrice = $price/($qty*1000);
}
else if($scale=="g")
{
    $unitPrice = $price/($qty);
}
else if($scale=="pcs")
{
    $unitPrice = $price/($qty);
}

// Handle image upload
$targetDir = "upload/";
$fileName = basename($_FILES["productImage"]["name"]);
$targetFilePath = $targetDir . $fileName;

if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFilePath)) {
    $picture = $targetFilePath;
} 
else {
    $picture = "";
}

// Insert product data into the database
$sql = "INSERT INTO product (storeID, productName, category, subCategory, description, brand, storageRequirements, discounts, specialDetails, stockAmount, price, image, quantity, scale, unitPrice, sellerID) 
        VALUES ('$storeID', '$productName', '$category', '$subCategory', '$description', '$brand', '$storageRequirements', '$discounts', '$specialDetails', '$stockAmount', '$price', '$picture', '$qty', '$scale', '$unitPrice', '$sellerID')";
}
// Execute the query and check if it was successful
if (mysqli_query($conn, $sql)) {
    echo "<h3>Product successfully inserted into the table.</h3>";
} 
else {
    echo "ERROR: Hush! SORRY $sql. " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>

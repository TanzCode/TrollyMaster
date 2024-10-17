<?php 
session_start();
include('conn.php');

// Retrieve the search keyword from the GET request
$search = $_GET['search'];

// Build the query with a LIKE clause to find similar product names
$query = "SELECT * FROM product WHERE productName LIKE ?";

// Prepare the statement
$stmt = $conn->prepare($query);

// Bind the parameter for the search keyword
$searchParam = "%" . $search . "%";
$stmt->bind_param('s', $searchParam);

$stmt->execute();
$result = $stmt->get_result();

// Initialize an array to store products and find the cheapest unit price
$products = [];
$cheapestUnitPrice = PHP_INT_MAX;

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
    if ($row['unitPrice'] < $cheapestUnitPrice) {
        $cheapestUnitPrice = $row['unitPrice'];
    }
}

// Include CSS for styling the cards
echo "<style>
    .cards-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }
    .product-item {
        border: 1px solid darkorange;
        margin-bottom: 20px;
        background: #fff;
        border-radius: 5px;
        overflow: hidden;
        transition: transform 0.2s;
        width: 300px;
        padding: 15px;
        box-sizing: border-box;
    }
    .product-item:hover {
        transform: scale(1.05);
    }
    .product-img img {
        max-width: 100%;
        border-radius: 5px 5px 0 0;
    }
    .product-item h4 {
        font-size: 18px;
        margin-bottom: 10px;
    }
    .product-item p {
        font-size: 14px;
        color: #6c757d;
    }
    .d-flex {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .btn-custom {
        color: #4CAF50;
        background-color: #ffffff;
        border: 1px solid darkorange;
        border-radius: 20px;
        padding: 10px 20px;
        text-align: center;
        transition: background-color 0.3s, color 0.3s, border-color 0.3s;
    }
    .btn-custom:hover {
        background-color: darkorange;
        color: #ffffff;
        border-color: darkorange;
    }
        .cheaper-products-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        margin-top: 40px;
    }
</style>";

?>

<!DOCTYPE html>
<html lang="en">
<?php
include('header.php');
?>

<br>
<br>
<br>
<br><br>
<br>
<div>
<?php 
// Check if any products were found
if ($result->num_rows > 0) {
    echo "<h2>Products</h2>";
    echo "<div class='cards-container'>";

    foreach ($products as $row){
        echo "<div class='product-item'>
                <div class='product-img'>
                    <img src='Seller/" . htmlspecialchars($row['image']) . "' alt='Product Image'>
                </div>
                <h4>" . htmlspecialchars($row['productName']) . "</h4>
                <p><strong>Category:</strong> " . htmlspecialchars($row['category']) . "</p>
                <p><strong>Sub Category:</strong> " . htmlspecialchars($row['subCategory']) . "</p>
                <p><strong>Description:</strong> " . htmlspecialchars($row['description']) . "</p>
                <p><strong>Brand:</strong> " . htmlspecialchars($row['brand']) . "</p>
                <p><strong>Storage Requirements:</strong> " . htmlspecialchars($row['storageRequirements']) . "</p>
                <p><strong>Discounts:</strong> " . htmlspecialchars($row['discounts']) . "</p>
                <p><strong>Special Details:</strong> " . htmlspecialchars($row['specialDetails']) . "</p>
                <p><strong>Stock Amount:</strong> " . htmlspecialchars($row['stockAmount']) . "</p>
                <p><strong>Price:</strong> " . htmlspecialchars($row['price']) . "</p>
                <p><strong>Unit Price:</strong> " . htmlspecialchars($row['unitPrice']) . "</p>
                <div class='d-flex'>
                    <button class='btn btn-custom'>Add to Order</button>
                </div>
            </div>";
    }
    echo "</div>";
}
    else {
        echo "No products found matching the search criteria.";
    }
    
// Display the cheapest products
echo "<h2>Cheapest Products</h2>";
echo "<div class='cheaper-products-container'>";

foreach ($products as $row) {
    if ($row['unitPrice'] == $cheapestUnitPrice) {
        echo "<div class='product-item'>
                <div class='product-img'>
                    <img src='Seller/" . htmlspecialchars($row['image']) . "' alt='Product Image'>
                </div>
                <h4>" . htmlspecialchars($row['productName']) . "</h4>
                <p><strong>Category:</strong> " . htmlspecialchars($row['category']) . "</p>
                <p><strong>Sub Category:</strong> " . htmlspecialchars($row['subCategory']) . "</p>
                <p><strong>Description:</strong> " . htmlspecialchars($row['description']) . "</p>
                <p><strong>Brand:</strong> " . htmlspecialchars($row['brand']) . "</p>
                <p><strong>Storage Requirements:</strong> " . htmlspecialchars($row['storageRequirements']) . "</p>
                <p><strong>Discounts:</strong> " . htmlspecialchars($row['discounts']) . "</p>
                <p><strong>Special Details:</strong> " . htmlspecialchars($row['specialDetails']) . "</p>
                <p><strong>Stock Amount:</strong> " . htmlspecialchars($row['stockAmount']) . "</p>
                <p><strong>Price:</strong> " . htmlspecialchars($row['price']) . "</p>
                <p><strong>Unit Price:</strong> " . htmlspecialchars($row['unitPrice']) . "</p>
                <div class='d-flex'>
                    <button class='btn btn-custom'>Buy Now</button>
                </div>
            </div>";
    }
    
}

echo "</div>";


$stmt->close();

?>
</div>
</body>
</html>
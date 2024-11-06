<?php
// Include the database connection
include 'dbConnection.php'; // Assumes you have a conn.php file to connect to your database

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a product ID is passed in the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Prepare the query for the product
    $productQuery = "SELECT * FROM product WHERE productID = ?";
    $stmt = $conn->prepare($productQuery);

    // Check if the query preparation was successful
    if ($stmt === false) {
        die('Query preparation failed: ' . $conn->error . ' - Query: ' . $productQuery);
    }

    // Bind parameters and execute the query
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $productResult = $stmt->get_result();
    
    if ($productResult->num_rows > 0) {
        $product = $productResult->fetch_assoc();

        // Fetch shop details from the `store` table
        $shopQuery = "SELECT * FROM store WHERE regID = ?";
        $shopStmt = $conn->prepare($shopQuery);

        // Check if the query preparation was successful for the shop query
        if ($shopStmt === false) {
            die('Query preparation failed: ' . $conn->error . ' - Query: ' . $shopQuery);
        }

        $shopStmt->bind_param("i", $product['storeID']);
        $shopStmt->execute();
        $shopResult = $shopStmt->get_result();

        // Check if the shop exists
        if ($shopResult->num_rows > 0) {
            $shop = $shopResult->fetch_assoc();
        } else {
            // If no shop found, set $shop to null to avoid further warnings
            $shop = null;
        }

        // Fetch seller contact details from the `seller_personal` table
        $sellerQuery = "SELECT phone, email FROM seller_personal WHERE sellerID = ?";
        $sellerStmt = $conn->prepare($sellerQuery);

        // Check if the query preparation was successful for the seller query
        if ($sellerStmt === false) {
            die('Query preparation failed: ' . $conn->error . ' - Query: ' . $sellerQuery);
        }

        $sellerStmt->bind_param("i", $product['sellerID']);
        $sellerStmt->execute();
        $sellerResult = $sellerStmt->get_result();

        // Check if the seller exists
        if ($sellerResult->num_rows > 0) {
            $seller = $sellerResult->fetch_assoc();
        } else {
            // If no seller found, set $seller to null
            $seller = null;
        }

    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "No product selected.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['productName']); ?> - Product Details</title>
    <link rel="stylesheet" href="css/home.css"> 
    <link rel="stylesheet" href="css/productView.css"> <!-- Assuming you have a CSS file for styling -->
</head>
<body>

<div class="product-view">
    <div class="product-details">
        <h2><?php echo htmlspecialchars($product['productName']); ?></h2>
        <p><strong>Price:</strong> <?php echo number_format($product['price'], 2); ?> </p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
        <img src="../../Seller/backend/product/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['productName']); ?>" style="max-width: 100%; height: auto;">
    </div>

    <?php if ($shop): ?>
        <div class="shop-details">
            <h3>Shop: <?php echo htmlspecialchars($shop['name']); ?></h3>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($shop['streetAddress']) . ', ' . htmlspecialchars($shop['city']) . ', ' . htmlspecialchars($shop['province']) . ' ' . htmlspecialchars($shop['postalCode']); ?></p>

        </div>
    <?php else: ?>
        <div class="shop-details">
            <h3>Shop details not available.</h3>
        </div>
    <?php endif; ?>

    <?php if ($seller): ?>
        <div class="seller-contact">
            <h3>Seller Contact Details</h3>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($seller['phone']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($seller['email']); ?></p>
        </div>
    <?php else: ?>
        <div class="seller-contact">
            <h3>Seller contact details not available.</h3>
        </div>
    <?php endif; ?>

</div>

</body>
</html>

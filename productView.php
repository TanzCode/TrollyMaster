<?php
// Include the database connection
include 'conn.php'; // Assumes you have a dbConnection.php file to connect to your database

// Check if a product ID is passed in the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch product details
    $productQuery = "SELECT * FROM product WHERE productID = ?";
    $stmt = $conn->prepare($productQuery);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $productResult = $stmt->get_result();
    
    if ($productResult->num_rows > 0) {
        $product = $productResult->fetch_assoc();

        // Fetch shop details
        $shopQuery = "SELECT * FROM store WHERE id = ?";
        $shopStmt = $conn->prepare($shopQuery);
        $shopStmt->bind_param("i", $product['shop_id']);
        $shopStmt->execute();
        $shopResult = $shopStmt->get_result();
        $shop = $shopResult->fetch_assoc();

        // Fetch shop ratings
        /*
        $ratingsQuery = "SELECT AVG(rating) as average_rating, COUNT(*) as total_ratings FROM shopratings WHERE shop_id = ?";
        $ratingStmt = $conn->prepare($ratingsQuery);
        $ratingStmt->bind_param("i", $product['shop_id']);
        $ratingStmt->execute();
        $ratingResult = $ratingStmt->get_result();
        $ratings = $ratingResult->fetch_assoc();
        */
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
    <title><?php echo $product['name']; ?> - Product Details</title>
    <link rel="stylesheet" href="css/home.css"> 
    <link rel="stylesheet" href="css/productView.css"> <!-- Assuming you have a CSS file for styling -->
</head>
<body>

<div class="product-view">
    <div class="product-details">
        <h2><?php echo $product['name']; ?></h2>
        <p><strong>Price:</strong> <?php echo $product['price']; ?></p>
        <p><strong>Description:</strong> <?php echo $product['description']; ?></p>
        <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
    </div>

    <div class="shop-details">
        <h3>Shop: <?php echo $shop['name']; ?></h3>
        <p><strong>Address:</strong> <?php echo $shop['address']; ?></p>
        <p><strong>Contact:</strong> <?php echo $shop['contact']; ?></p>
        <p><strong>Email:</strong> <?php echo $shop['email']; ?></p>
    </div>

    <div class="shop-ratings">
        <h3>Shop Ratings</h3>
        <p>Average Rating: <?php echo round($ratings['average_rating'], 1); ?> / 5 (<?php echo $ratings['total_ratings']; ?> reviews)</p>
    </div>
</div>

</body>
</html>

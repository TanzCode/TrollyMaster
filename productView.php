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
            $shop = null;
        }

        // Fetch seller contact details from the `seller_personal` table
        $sellerQuery = "SELECT * FROM seller_personal WHERE sellerID = ?";
        $sellerStmt = $conn->prepare($sellerQuery);

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
    <link rel="stylesheet" href="css/productView.css">
    <?php include 'header.php';?>
    <style>
       /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 0;
        }

        .product-view {
            max-width: 1200px;
            margin: 190px auto; /* Centers the element horizontally with a top margin */
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Centers child elements horizontally */
            align-items: center; /* Centers child elements vertically */
        }

        .product-details {
            flex: 1 1 60%;
            padding: 20px;
        }

        .product-details h2 {
            font-size: 2rem;
            color: #4CAF50;
        }

        .product-details p {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .product-details .product-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Combined Shop & Seller Details */
        .shop-seller-details {
            flex: 1 1 35%;
            padding: 20px;
            margin-left: 20px;
            background-color: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .shop-details, .seller-contact {
            margin-bottom: 20px;
        }

        .shop-details h3, .seller-contact h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #333;
        }

        .shop-details p, .seller-contact p {
            font-size: 1rem;
            color: #555;
        }

        /* Add to Cart Button */
        .add-to-cart {
            display: inline-block;
            padding: 12px 20px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 8px;
            text-decoration: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .add-to-cart:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .add-to-cart i {
            margin-right: 8px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .product-view {
                flex-direction: column;
            }

            .product-details, .shop-seller-details {
                flex: 1 1 100%;
                margin-left: 0;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
<div class="product-view">
    <!-- Product Details -->
    <div class="product-details">
        <h2><?php echo htmlspecialchars($product['productName']); ?></h2>
        <h4><strong>LKR </strong> <?php echo number_format($product['price'], 2); ?> </h4>
        
        <h4><strong></strong> <?php echo htmlspecialchars($product['brand']); ?></h4>
        
        <img src="../../Seller/backend/product/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['productName']); ?>" class="product-image">
        <br>
        <br>
        <p><strong>Unit Price</strong> <?php echo number_format( $product['unitPrice'] ,2 )?></p>
        <p><strong>Stock Availability:</strong> <?php echo $product['stockAmount'] > 0 ? 'In Stock' : 'Out of Stock'; ?></p>
        <p><strong>Storage Requirements:</strong> <?php echo htmlspecialchars($product['storageRequirements']); ?></p>
        <p><strong>Special Details:</strong> <?php echo htmlspecialchars($product['specialDetails']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
        <!-- Add to Cart Button -->
        <a href="cart.php?productID=<?php echo $product['productID']; ?>" class="btn btn-custom add-to-cart">
            <i class="fa fa-shopping-cart cart"></i> Add to cart
        </a>
    </div>

    <!-- Shop and Seller Details -->
    <div class="shop-seller-details">
        <!-- Shop Details -->
        <?php if ($shop): ?>
            <div class="shop-details">
                <h3><?php echo htmlspecialchars($shop['name']); ?></h3>
                <p><strong>Logo:</strong> <img src="../../Seller/backend/uploadlogo/<?php echo htmlspecialchars($shop['logo']); ?>" alt="Shop Logo" style="max-width: 100px; height: auto;"></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($shop['streetAddress']) . ', ' . htmlspecialchars($shop['city']) . ', ' . htmlspecialchars($shop['province']) . ' ' . htmlspecialchars($shop['postalCode']); ?></p>
            </div>
        <?php else: ?>
            <div class="shop-details">
                <h3>Shop details not available.</h3>
            </div>
        <?php endif; ?>

        <!-- Seller Contact Details -->
        <?php if ($seller): ?>
            <div class="seller-contact">
                <h3>Seller: <?php echo htmlspecialchars($seller['fName']) . ' ' . htmlspecialchars($seller['lName']); ?></h3>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($seller['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($seller['phone']); ?></p>
                
                <p><strong>Address:</strong> <?php echo htmlspecialchars($seller['perAddress']); ?></p>
            </div>
        <?php else: ?>
            <div class="seller-contact">
                <h3>Seller contact details not available.</h3>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php';?>
</body>
</html>

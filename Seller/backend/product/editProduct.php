<?php
// Start the session
session_start();

// Check if the first name is set in the session
if (isset($_SESSION['fName']) && isset($_SESSION['lName']) && isset($_SESSION['sellerID'])) {
    $firstName = $_SESSION['fName'];
    $lastName = $_SESSION['lName'];
    $userID = $_SESSION['sellerID'];
} else {
    echo "Session data not set.";
    exit;
}

// Check if productID is passed via the URL
if (isset($_GET['id'])) {
    $productID = $_GET['id'];

    // Database connection
    include 'dbConnection.php';

    // Fetch the product data
    $sql = "SELECT * FROM product WHERE productID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "No product ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../CSS/slider.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <link href="styleproductInsert.css" rel="stylesheet">
    <title>Edit Product</title>
</head>
<body>
<header class="header" id="header">
        <div class="header_toggle">
            <i class='bx bx-menu' id="header-toggle"></i>
        </div>
        <div class="header_img">
            <img src="pro.jpg" alt="Profile Image">
        </div>
    </header>
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="../home.php" class="nav_logo">
                    <i class='bx bx-layer nav_logo-icon'></i>
                    <span class="nav_logo-name">Trolly Master</span>
                </a>
                <div class="nav_list">
                    <a href="../overview.php" class="nav_link">
                        <i class='bx bx-grid-alt nav_icon'></i>
                        <span class="nav_name">Overview</span>
                    </a>
                    <div class="nav_item dropdown">
                        <a href="#" class="nav_link dropdown-toggle" id="customerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class='bx bx-clipboard' ></i>
                            <span class="nav_name">Product</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="customerDropdown">
                            <li><a class="dropdown-item" href="addProducts.php" ><i class='bx bxs-add-to-queue'></i><span class="nav_name">Add new Product</span></a></li>
                            <li><a class="dropdown-item" href="updateProducts.php" class="nav_link active" ><i class='bx bxs-book-open' ></i><span class="nav_name">View all products</span></a></li>
                            <li><a class="dropdown-item" href="viewProducts.php"><i class='bx bx-receipt'></i><span class="nav_name">Update product list</span></a></li>
                        </ul>
                    </div>
                    <a href="../orderManagement.php"  class="nav_link">
                        <i class='bx bxs-package' ></i><span class="nav_name">Orders</span>
                    </a>

                    <a href="../shopManagement.php" class="nav_link">
                        <i class='bx bx-store'></i><span class="nav_name">Shop management</span>
                    </a>
                    <a href="../notification.php" class="nav_link" >
                        <i class='bx bx-message-square-detail nav_icon'></i><span class="nav_name" >Notification</span>
                    </a>
                    <a href="../profile.php" class="nav_link">
                        <i class='bx bx-cog' ></i><span class="nav_name">Profile management</span>
                    </a>
                    
                </div>
            </div>
            <a href="../../login.html" class="nav_link">
                <i class='bx bx-log-out nav_icon'></i><span class="nav_name">Sign Out</span>
            </a>
        </nav>
    </div>

    <!-- Product Edit Form -->
    <div class="height-100 bg-light">
        <div class="welcome-message">
            <h1>Welcome, <?php echo htmlspecialchars($firstName) . " " . htmlspecialchars($lastName); ?>!</h1>
            <p>Seller ID: <span id="user-id"><?php echo htmlspecialchars($userID); ?></span></p>
        </div>

        <hr>
    <div class="container">
        <h1>Edit Product</h1>
        <form action="editedProduct.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="productID" value="<?php echo htmlspecialchars($row['productID']); ?>" >

            <label for="productName">Product Name:</label>
            <input type="text" id="productName" name="productName" value="<?php echo htmlspecialchars($row['productName']); ?>" required>

            <label for="category">Category:</label>
            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($row['category']); ?>" required>

            <label for="subCategory">Sub-Category:</label>
            <input type="text" id="subCategory" name="subCategory" value="<?php echo htmlspecialchars($row['subCategory']); ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($row['description']); ?></textarea>

            <label for="brand">Brand:</label>
            <input type="text" id="brand" name="brand" value="<?php echo htmlspecialchars($row['brand']); ?>" required>

            <label for="storageRequirements">Storage Requirements:</label>
            <input type="text" id="storageRequirements" name="storageRequirements" value="<?php echo htmlspecialchars($row['storageRequirements']); ?>" required>

            <label for="discounts">Discounts:</label>
            <input type="text" id="discounts" name="discounts" value="<?php echo htmlspecialchars($row['discounts']); ?>">

            <label for="specialDetails">Special Details:</label>
            <textarea id="specialDetails" name="specialDetails"><?php echo htmlspecialchars($row['specialDetails']); ?></textarea>

            <label for="stockAmount">Stock Amount:</label>
            <input type="number" id="stockAmount" name="stockAmount" value="<?php echo htmlspecialchars($row['stockAmount']); ?>" required>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" required>

            <label for="image">Product Image:</label>
            <input type="file" id="image" name="image">
            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image" width="100px">

            <button type="submit">Update Product</button>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>

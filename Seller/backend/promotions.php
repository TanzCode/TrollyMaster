<?php
session_start();
include 'dbConnection.php'; // Ensure this file contains the connection setup

// Check if the session variables for the seller are set
if (isset($_SESSION['fName']) && isset($_SESSION['lName']) && isset($_SESSION['sellerID'])) {
    $firstName = $_SESSION['fName'];
    $lastName = $_SESSION['lName'];
    $userID = $_SESSION['sellerID'];
} else {
    echo "Seller not logged in.";
    header("Location: ../login.html");
    exit();
}

include 'dbConnection.php';

$sellerID = $_SESSION['sellerID'];
$storeID = $_SESSION['storeID'];

// Fetch products associated with this seller
$sql = "SELECT productID, productName, price, discounts, specialDetails FROM product WHERE sellerID = '$sellerID'";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="CSS/slider.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
   
    <link href="CSS/stylePromotionDiscount.css" rel="stylesheet">
    <style>body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

h1 {
    text-align: center;
    color: #333;
    margin-top: 20px;
    font-size: 28px;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 40px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.product-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.product-table thead th {
    background-color: #81C408;
    color: #fff;
    padding: 12px;
    text-align: left;
    font-weight: bold;
}

.product-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.product-table tbody tr:hover {
    background-color: #f1f1f1;
}

.product-table th, .product-table td {
    padding: 10px;
    border: 1px solid #ddd;
}

.product-table input[type="number"],
.product-table input[type="text"] {
    padding: 8px;
    width: 100%;
    box-sizing: border-box;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.btn-submit {
    display: block;
    width: 100%;
    max-width: 200px;
    margin: 20px auto;
    padding: 12px;
    background-color: #008000;
    color: white;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-submit:hover {
    background-color: darkorange;
}
</style>
    <title>Dashboard - Seller | Manage Promotions & Discounts</title>
</head>
<body id="body-pd">
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
                            <i class='bx bx-clipboard'></i>
                            <span class="nav_name">Product</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="customerDropdown">
                            <li><a class="dropdown-item" href="addProducts.php"><i class='bx bxs-add-to-queue'></i> Add New Product</a></li>
                            <li><a class="dropdown-item" href="updateProducts.php"><i class='bx bxs-book-open'></i> View All Products</a></li>
                            <li><a class="dropdown-item" href="viewProducts.php"><i class='bx bx-receipt'></i> Update Product List</a></li>
                        </ul>
                    </div>
                    <a href="../orderManagement.php" class="nav_link">
                        <i class='bx bxs-package'></i>
                        <span class="nav_name">Orders</span>
                    </a>
                    <a href="promotionDiscount.php" class="nav_link active">
                        <i class='bx bx-gift'></i>
                        <span class="nav_name">Promotions & Discounts</span>
                    </a>
                    <a href="../shopManagement.php" class="nav_link">
                        <i class='bx bx-store'></i>
                        <span class="nav_name">Shop Management</span>
                    </a>
                </div>
            </div>
            <a href="../../login.html" class="nav_link">
                <i class='bx bx-log-out nav_icon'></i>
                <span class="nav_name">Sign Out</span>
            </a>
        </nav>
    </div>

    <div class="height-100 bg-light">
        <div class="container">
            <h1>Manage Promotions and Discounts</h1>
            
            <?php if (mysqli_num_rows($result) > 0): ?>
                <form action="updatePromotions.php" method="post">
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Current Price</th>
                                <th>Discount (%)</th>
                                <th>Special Details</th>
                                <th>New Discount</th>
                                <th>Promotion Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['productName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                                    <td><?php echo htmlspecialchars($row['discounts']); ?></td>
                                    <td><?php echo htmlspecialchars($row['specialDetails']); ?></td>
                                    <td>
                                        <input type="number" name="discount[<?php echo $row['productID']; ?>]" min="0" max="100" placeholder="Discount %" />
                                    </td>
                                    <td>
                                        <input type="text" name="specialDetails[<?php echo $row['productID']; ?>]" placeholder="Promotion Details" />
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="btn-submit">Update Promotions</button>
                </form>
            <?php else: ?>
                <p>No products found for your store.</p>
            <?php endif; ?>

        </div>
    </div>

    <script src="../JS/slider.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../JS/slider.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>

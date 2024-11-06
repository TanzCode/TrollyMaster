<?php
session_start();

// Check if the first name is set in the session
if (isset($_SESSION['fName']) && isset($_SESSION['lName']) && isset($_SESSION['sellerID'])) {
    $firstName = $_SESSION['fName'];
    $lastName = $_SESSION['lName'];
    $userID = $_SESSION['sellerID'];
   
    // You can now use $firstName in your HTML or other PHP code
} else {
    echo "First name not set.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../CSS/slider.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
   
    <link href="styleproductInsert.css" rel="stylesheet">
    <title>Dashboard - Seller | Add Product</title>
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
                            <i class='bx bx-clipboard' ></i>
                            <span class="nav_name">Product</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="customerDropdown">
                            <li><a class="dropdown-item" href="addProducts.php" class="nav_link active"><i class='bx bxs-add-to-queue'></i><span class="nav_name">Add new Product</span></a></li>
                            <li><a class="dropdown-item" href="updateProducts.php" ><i class='bx bxs-book-open' ></i><span class="nav_name">View all products</span></a></li>
                            <li><a class="dropdown-item" href="viewProducts.php"><i class='bx bx-receipt'></i><span class="nav_name">Update product list</span></a></li>
                        </ul>
                    </div>
                    <a href="../orderManagement.php"  class="nav_link">
                        <i class='bx bxs-package' ></i><span class="nav_name">Orders</span>
                    </a>

                    <a href="../shopManagement.php" class="nav_link">
                        <i class='bx bx-store'></i><span class="nav_name">Shop management</span>
                    </a>
                    <a href="../promotions.php" class="nav_link">
                        <i class='bx bxs-gift'></i><span class="nav_name">Promotions</span>
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
    <!--Container Main start-->
    <div class="height-100 bg-light">
        <div class="welcome-message" style="margin-top:80px; ">
            <h1>Welcome, <?php echo htmlspecialchars($firstName) . " " . htmlspecialchars($lastName); ?>!</h1>
            <p>Seller ID: <span id="user-id"><?php echo htmlspecialchars($userID); ?></span></p>
        </div>

        <hr>
        <form action="productInsert.php" method="post" enctype="multipart/form-data" class="product-form">
            <h2>Insert Product</h2>

            <div class="form-group">
                <label for="storeID">Store ID</label>
                <input type="text" id="storeID" name="storeID" required>
            </div>

            <div class="form-group">
                <label for="productName">Product Name</label>
                <input type="text" id="productName" name="productName" required>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="">Select a category</option>
                    <option value="fruitsVegetables">Fruits and Vegetables</option>
                    <option value="dairy">Dairy</option>
                    <option value="pantryStaples">Pantry Staples</option>
                    <option value="beverages">Beverages</option>
                    <option value="snacksSweets">Snacks and Sweets</option>
                    <option value="personalCare">Personal care</option>
                    <option value="household">Household</option>
                    <option value="babyProducts">Baby Products</option>
                </select>
            </div>

            <div class="form-group">
                <label for="sub_category">Sub Category</label>
                <select id="subcategory" name="subcategory" required>
                    <option value="">Select a subcategory</option>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" id="price" name="price" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="qty">Quantity</label>
                    <input type="text" id="qty" name="qty" required>                        
                </div>
                <div class="form-group">
                    <label for="postal-code">Scale:</label>
                    <select id="scale" name="scale" required>
                        <option value="">Select a subcategory</option>
                        <option value="g">g</option>
                        <option value="kg">kg</option>
                        <option value="pcs">pcs</option>
                        <option value="ml">ml</option>
                        <option value="l">l</option>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label for="productImage">Images</label>
                <input type="file" id="productImage" name="productImage"  required>
             
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="brand">Brand</label>
                <input type="text" id="brand" name="brand" required>
            </div>

            <div class="form-group">
                <label for="storage_requirements">Storage Requirements</label>
                <input type="text" id="storage_requirements" name="storage_requirements" required>
            </div>

            <div class="form-group">
                <label for="discounts">Discounts</label>
                <input type="text" id="discounts" name="discounts" required>
            </div>

            <div class="form-group">
                <label for="special_details">Special Details</label>
                <input type="text" id="special_details" name="special_details" required>
            </div>

            <div class="form-group">
                <label for="stock_amount">Stock Amount</label>
                <input type="text" id="stock_amount" name="stock_amount" required>
            </div>
            <div class="form-submit">
                <button type="submit" id="insert" class="submit" style="margin-bottom:30px;">Insert Product</button>
            </div>
        </form>

    <script src="script.js"></script>
    <!--Container Main end-->
    <script src="../JS/slider.js"></script>
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
session_start();
include 'dbConnection.php'; // Ensure this file contains the connection setup

// Check if the session variables for the seller are set
if (isset($_SESSION['fName']) && isset($_SESSION['lName']) && isset($_SESSION['sellerID'])) {
    $firstName = $_SESSION['fName'];
    $lastName = $_SESSION['lName'];
    $sellerID = $_SESSION['sellerID'];
} else {
    // Redirect to login page if not logged in
    header("Location: ../login.html");
    exit();
}

// Fetch products associated with the seller
$sql = "SELECT productID, productName FROM product WHERE sellerID = '$sellerID'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="CSS/slider.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard - Seller</title>
    <style>
        /* General Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f8; color: #333; }

        /* Container */
        .container { max-width: 1200px; padding: 20px; margin: auto; }

        /* Welcome Message */
        .welcome-message { text-align: center; margin-top: 20px; }
        .welcome-message h1 { font-size: 2.2rem; color: #333; }

        /* Form Styling */
        form {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }

        h2 { text-align: center; color: #333; margin-bottom: 1.5rem; }

        /* Labels and Input Fields */
        label { font-weight: 500; color: #555; display: block; margin-top: 1rem; }
        input[type="text"], input[type="number"], input[type="date"], textarea {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 1rem;
        }

        input:focus, textarea:focus { border-color: #81c408; box-shadow: 0 0 5px rgba(129, 196, 8, 0.3); outline: none; }

        /* Button Styling */
        button[type="submit"] {
            width: 100%; padding: 15px; font-size: 1.1rem; color: #fff; background-color: #81c408;
            border: none; border-radius: 8px; cursor: pointer; transition: 0.3s;
        }

        button:hover { background-color: #6dae00; }

        /* Product Selection Section */
        h3 { font-size: 1.2rem; margin-top: 2rem; color: #555; }
        .product-checkbox label { display: flex; align-items: center; cursor: pointer; }
        .product-checkbox input { margin-right: 10px; }
    </style>
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
                <a href="overview.php" class="nav_logo">
                    <i class='bx bx-layer nav_logo-icon'></i>
                    <span class="nav_logo-name">Trolly Master</span>
                </a>
                <div class="nav_list">
                    <a href="overview.php" class="nav_link">
                        <i class='bx bx-grid-alt nav_icon'></i>
                        <span class="nav_name">Overview</span>
                    </a>
                    <div class="nav_item dropdown">
                        <a href="#" class="nav_link dropdown-toggle" id="customerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class='bx bx-clipboard' ></i>
                            <span class="nav_name">Product</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="customerDropdown">
                            <li><a class="dropdown-item" href="product/addProducts.php"><i class='bx bxs-add-to-queue'></i><span class="nav_name">Add new Product</span></a></li>
                            <li><a class="dropdown-item" href="product/updateProducts.php" ><i class='bx bxs-book-open' ></i><span class="nav_name">View all products</span></a></li>
                            <li><a class="dropdown-item" href="product/viewProducts.php"><i class='bx bx-receipt'></i><span class="nav_name">Update product list</span></a></li>
                        </ul>
                    </div>
                    <a href="orderManagement.php"  class="nav_link">
                        <i class='bx bxs-package' ></i><span class="nav_name">Orders</span>
                    </a>

                    <a href="shopManagement.php" class="nav_link">
                        <i class='bx bx-store'></i><span class="nav_name">Shop management</span>
                    </a>
                    <a href="promotions.php" class="nav_link"  class="nav_link active">
                        <i class='bx bxs-gift'></i><span class="nav_name">Promotions</span>
                    </a>
                    <a href="notification.php" class="nav_link">
                        <i class='bx bx-message-square-detail nav_icon'></i><span class="nav_name" >Notification</span>
                    </a>
                    <a href="profile.php" class="nav_link">
                        <i class='bx bx-cog' ></i><span class="nav_name">Profile management</span>
                    </a>
                    
                </div>
            </div>
            <a href="../login.html" class="nav_link">
                <i class='bx bx-log-out nav_icon'></i><span class="nav_name">Sign Out</span>
            </a>
        </nav>
    </div>


    <div class="container" style="margin-top:80px;">
        <div class="welcome-message">
            <h1>Welcome, <?php echo htmlspecialchars($firstName) . " " . htmlspecialchars($lastName); ?>!</h1>
            <p>Seller ID: <?php echo htmlspecialchars($sellerID); ?></p>
        </div>

        <h2>Create New Promotion</h2>

        <form action="createPromotion.php" method="post">
            <label for="promotionName">Promotion Name:</label>
            <input type="text" name="promotionName" id="promotionName" required>

            <label for="promotionDetails">Promotion Details:</label>
            <textarea name="promotionDetails" id="promotionDetails" required></textarea>

            <label for="discount">Discount (%):</label>
            <input type="number" name="discount" id="discount" min="0" max="100" required>

            <label for="startDate">Start Date:</label>
            <input type="date" name="startDate" id="startDate" required>

            <label for="endDate">End Date:</label>
            <input type="date" name="endDate" id="endDate" required>

            <h3>Select Products for Promotion</h3>
            <div class="product-checkbox">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <label>
                        <input type="checkbox" name="products[]" value="<?php echo $row['productID']; ?>">
                        <?php echo $row['productName'] . " (ID : " . $row['productID'] . ")"; ?>
                    </label>
                <?php endwhile; ?>
            </div>

            <button type="submit">Create Promotion</button>
        </form>
    </div>
    <script src="JS/slider.js"></script>
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

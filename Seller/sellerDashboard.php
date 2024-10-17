<?php
session_start();

// Check if the first name is set in the session
if (isset($_SESSION['firstName']) && isset($_SESSION['lastName']) && isset($_SESSION['userID'])) {
    $firstName = $_SESSION['firstName'];
    $lastName = $_SESSION['lastName'];
    $userID = $_SESSION['userID'];
   
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
    <link href="dash.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard - Seller</title>
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
                <a href="#" class="nav_logo">
                    <i class='bx bx-layer nav_logo-icon'></i>
                    <span class="nav_logo-name">Trolly Master</span>
                </a>
                <div class="nav_list">
                    <a href="#" class="nav_link active">
                        <i class='bx bx-grid-alt nav_icon'></i>
                        <span class="nav_name">Overview</span>
                    </a>
                    <div class="nav_item dropdown">
                        <a href="#" class="nav_link dropdown-toggle" id="customerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class='bx bx-clipboard' ></i>
                            <span class="nav_name">Product</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="customerDropdown">
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="newProduct()"><i class='bx bxs-add-to-queue'></i><span class="nav_name">Add new Product</span></a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="viewProductList()"><i class='bx bxs-book-open' ></i><span class="nav_name">View all product list</span></a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="updateProduct()"><i class='bx bx-receipt'></i>
                                <span class="nav_name">Update product list</span></a></li>
                        </ul>
                    </div>
                    <a href="javascript:void(0)" onclick="seller()" class="nav_link">
                        <i class='bx bxs-package' ></i>
                        <span class="nav_name">Orders</span>
                    </a>

                    <a href="javascript:void(0)" onclick="shop()" class="nav_link">
                        <i class='bx bx-store'></i>
                        <span class="nav_name">Shop management</span>
                    </a>
                    <a href="#" class="nav_link">
                        <i class='bx bx-message-square-detail nav_icon'></i>
                        <span class="nav_name">Notification</span>
                    </a>
                    <a href="javascript:void(0)" onclick="shop()" class="nav_link">
                        <i class='bx bx-cog' ></i>
                        <span class="nav_name">Profile management</span>
                    </a>
                    
                </div>
            </div>
            <a href="login.html" class="nav_link">
                <i class='bx bx-log-out nav_icon'></i>
                <span class="nav_name">Sign Out</span>
            </a>
        </nav>
    </div>
    <!--Container Main start-->
    <div class="height-100 bg-light">
        <div class="welcome-message">
            <h1>Welcome, <?php echo htmlspecialchars($firstName) . " " . htmlspecialchars($lastName); ?>!</h1>
            <p>Seller ID: <span id="user-id"><?php echo htmlspecialchars($userID); ?></span></p>
        </div>
        <hr>
        <iframe id="mainContent" style="width: 100%; height: 80%;" frameborder="0" src="Home.html"></iframe>
    </div>
    <!--Container Main end-->
    <script src="dash.js"></script>
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        //product
            function newProduct() {
        document.getElementById("mainContent").src = "productInsert.html";
    }
    function viewProductList() {
        document.getElementById("mainContent").src = "viewAllProducts.php";
    }
    function updateProduct() {
        document.getElementById("mainContent").src = "updateProducts.php";
    }
    </script>
</body>
</html>

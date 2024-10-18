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
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="dash.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard</title>
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
                        <span class="nav_name">Dashboard</span>
                    </a>
                    <a href="#" class="nav_link">
                        <i class='bx bx-user nav_icon'></i>
                        <span class="nav_name">Customers</span>
                    </a>
                    <a href="javascript:void(0)" onclick="seller()" class="nav_link">
                        <i class='bx bxs-user-detail'></i>
                        <span class="nav_name">Sellers</span>
                    </a>
                    <a href="javascript:void(0)" onclick="shop()" class="nav_link">
                        <i class='bx bx-store'></i>
                        <span class="nav_name">Shop</span>
                    </a>
                    <a href="#" class="nav_link">
                        <i class='bx bx-message-square-detail nav_icon'></i>
                        <span class="nav_name">Notification</span>
                    </a>
                    <a href="javascript:void(0)" onclick="product()" class="nav_link">
                        <i class='bx bx-bookmark nav_icon'></i>
                        <span class="nav_name">Products</span>
                    </a>
                    <a href="#" class="nav_link">
                        <i class='bx bx-folder nav_icon'></i>
                        <span class="nav_name">Order</span>
                    </a>
                    <a href="#" class="nav_link">
                        <i class='bx bx-bar-chart-alt-2 nav_icon'></i>
                        <span class="nav_name">Stats</span>
                    </a>
                </div>
            </div>
            <a href="#" class="nav_link">
                <i class='bx bx-log-out nav_icon'></i>
                <span class="nav_name">Sign Out</span>
            </a>
        </nav>
    </div>
    <!--Container Main start-->
    <div class="height-100 bg-light">
    

                <iframe id="mainContent" style="width: 100%; height: 80%;" frameborder="0" src="Home.html"></iframe>
    </div>
    <!--Container Main end-->
    <script src="dash.js"></script>
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

   

</body>

<script>

function product() {
    document.getElementById("mainContent").src = "viewAllproduct.php";
}
function shop() {
    document.getElementById("mainContent").src = "viewAllShop.php";
}
function seller() {
    document.getElementById("mainContent").src = "viewAllSeller.php";
}
</script>
</html>

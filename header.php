<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trolly Master</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/home.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"><!--fontawesome-free-5.15.4-web-->
   <!--fontawesome-free-5.15.4-web-->
    <link rel="stylesheet" href="css/nevigation.css">
    <link rel="stylesheet" href="css/nav2style.css">
    <script src="js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/style.css">
    <style>
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .popup.show {
            display: flex;
        }

        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .card {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header id="header" class="header fixed-top">
        <div>
            <!-- First Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="index.html">
                    <img src="logo.png" alt="Logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <form class="search-form ms-auto" action="productearch.php" method="get">
                        <input type="text" name="search" placeholder="Search...">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.html">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.html"><i class="fas fa-shopping-cart"></i> Cart</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" id="loginBtn"><i class="fas fa-sign-out-alt"></i> Login</a>
                            <div id="popup" class="popup">
                                <div class="popup-content">
                                    <span id="closeBtn" class="close">&times;</span>
                                    <h2>Select Account Type</h2>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card" onclick="selectAccountType('customer')">
                                                <img src="img/customerloginprofilee.jpg" class="card-img-top" alt="Customer Account">
                                                <div class="card-body">
                                                    <h5 class="card-title">Customer Account</h5>
                                                    <button class="btn btn-primary">Select</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card" onclick="selectAccountType('Seller')">
                                                <img src="img/Sellerloginprofile.png" class="card-img-top" alt="Business Account">
                                                <div class="card-body">
                                                    <h5 class="card-title">Business Account</h5>
                                                    <button class="btn btn-primary">Select</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <form id="accountTypeForm" action="accountTypeHandler.php" method="post" style="display:none;">
                                        <input type="hidden" name="accountType" id="accountTypeInput">
                                        <button type="submit" class="btn btn-md-square">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Second Navbar -->
            <nav class="navbar2">
                <div class="container">
                    <ul class="list">
                        <li class="dropdown">
                            <a href="#">Fruits and Vegetables</a>
                            <div class="submenu">
                                <a href="category.php?category=fruitsVegetables&subCategory=Fresh%20Fruits">Fresh Fruits</a>
                                <a href="category.php?category=fruitsVegetables&subCategory=Fresh%20Vegetables">Fresh Vegetables</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a href="#">Dairy</a>
                            <div class="submenu">
                                <a href="category.php?category=Dairy&subCategory=Milk">Milk</a>
                                <a href="category.php?category=Dairy&subCategory=Cheese">Cheese</a>
                                <a href="category.php?category=Dairy&subCategory=Yogurt">Yogurt</a>
                                <a href="category.php?category=Dairy&subCategory=Butter">Butter</a>
                                <a href="category.php?category=Dairy&subCategory=Eggs">Eggs</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a href="#">Pantry Staples</a>
                            <div class="submenu">
                                <a href="category.php?category=pantryStaples&subCategory=Rice%20and%20Grains">Rice and Grains</a>
                                <a href="category.php?category=pantryStaples&subCategory=Pasta%20and%20Noodles">Pasta and Noodles</a>
                                <a href="category.php?category=pantryStaples&subCategory=Oils">Oils</a>
                                <a href="category.php?category=pantryStaples&subCategory=Sauces%20and%20Condiments">Sauces and Condiments</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a href="#">Beverages</a>
                            <div class="submenu">
                                <a href="category.php?category=Beverages&subCategory=Water">Water</a>
                                <a href="category.php?category=Beverages&subCategory=Juices">Juices</a>
                                <a href="category.php?category=Beverages&subCategory=Soft%20Drinks">Soft Drinks</a>
                                <a href="category.php?category=Beverages&subCategory=Coffee%20and%20Tea">Coffee and Tea</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a href="#">Snacks and Sweets</a>
                            <div class="submenu">
                                <a href="category.php?category=snacksSweets&subCategory=Chips%20and%20Crackers">Chips and Crackers</a>
                                <a href="category.php?category=snacksSweets&subCategory=Chocolates%20and%20Candies">Chocolates and Candies</a>
                                <a href="category.php?category=snacksSweets&subCategory=Nuts%20and%20Seeds">Nuts and Seeds</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a href="#">Personal Care</a>
                            <div class="submenu">
                                <a href="category.php?category=personalCare&subCategory=Toiletries">Toiletries</a>
                                <a href="category.php?category=personalCare&subCategory=Skincare">Skincare</a>
                                <a href="category.php?category=personalCare&subCategory=Haircare">Haircare</a>
                                <a href="category.php?category=personalCare&subCategory=Oral%20Care">Oral Care</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a href="#">Household</a>
                            <div class="submenu">
                                <a href="category.php?category=Household&subCategory=Cleaning%20Supplies">Cleaning Supplies</a>
                                <a href="category.php?category=Household&subCategory=Paper%20Products%20(Toilet%20Paper,%20Paper%20Towels)">Paper Products (Toilet Paper, Paper Towels)</a>
                                <a href="category.php?category=Household&subCategory=Laundry%20Supplies">Laundry Supplies</a>
                                <a href="category.php?category=Household&subCategory=Dishwashing%20Supplies">Dishwashing Supplies</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a href="#">Baby Products</a>
                            <div class="submenu">
                                <a href="category.php?category=babyProducts&subCategory=Baby%20Food">Baby Food</a>
                                <a href="category.php?category=babyProducts&subCategory=Diapers">Diapers</a>
                                <a href="category.php?category=babyProducts&subCategory=Baby%20Wipes">Baby Wipes</a>
                                <a href="category.php?category=babyProducts&subCategory=Baby%20Care">Baby Care</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            
        </div>
    </header>
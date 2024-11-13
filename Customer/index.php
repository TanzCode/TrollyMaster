<?php
include 'dbConnection.php';
function getNewProducts($conn) {
    $sql = "SELECT p.productID, p.productName, p.description, p.price, p.image, p.discounts, 
                   s.name AS storeName, s.logo AS storeLogo
            FROM product p
            JOIN store s ON p.storeID = s.regID
            ORDER BY p.productID DESC
            LIMIT 6";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to get discount promotion products
function getDiscountProducts($conn) {
    $today = date("Y-m-d");
    $sql = "SELECT p.productID, p.productName, p.description, p.price, p.image, p.discounts,
                   s.name AS storeName, s.logo AS storeLogo, promo.discount, promo.promotionName
            FROM product p
            JOIN promotions promo ON p.promotionID = promo.promotionID
            JOIN store s ON p.storeID = s.regID
            WHERE promo.startDate <= '$today' AND promo.endDate >= '$today'";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


$newProducts = getNewProducts($conn);
$discountProducts = getDiscountProducts($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Fruitables - Vegetable Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Fresh Fruits, Vegetables, Grocery" name="keywords">
    <meta content="Your one-stop shop for fresh fruits, vegetables, and all your grocery needs." name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">


    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">

    <!-- Navigation bar CSS -->
    <link rel="stylesheet" href="../css/nav2style.css">
    <link rel="stylesheet" href="../css/nevigation.css">
    <link rel="stylesheet" href="../css/home.css">
</head>

<body>
    <header id="header" class="header fixed-top">
        <div>
            <!-- First Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="index.php">
                    <img src="logo.png" alt="Logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <form class="search-form ms-auto" action="productSearch.php" method="get">
                        <input type="text" name="search" placeholder="Search...">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="backend/product.php">All Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="backend/viewCart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../index.php" id="loginBtn"><i class="fas fa-sign-out-alt"></i> Log Out</a>
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
                                <a href="backend/category.php?category=fruitsVegetables&subCategory=Fresh%20Fruits">Fresh Fruits</a>
                                <a href="backend/category.php?category=fruitsVegetables&subCategory=Fresh%20Vegetables">Fresh Vegetables</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a href="#">Dairy</a>
                            <div class="submenu">
                                <a href="backend/category.php?category=Dairy&subCategory=Milk">Milk</a>
                                <a href="backend/category.php?category=Dairy&subCategory=Cheese">Cheese</a>
                                <a href="backend/category.php?category=Dairy&subCategory=Yogurt">Yogurt</a>
                                <a href="backend/category.php?category=Dairy&subCategory=Butter">Butter</a>
                                <a href="backend/category.php?category=Dairy&subCategory=Eggs">Eggs</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a href="#">Pantry Staples</a>
                            <div class="submenu">
                                <a href="backend/category.php?category=pantryStaples&subCategory=Rice%20and%20Grains">Rice and Grains</a>
                                <a href="backend/category.php?category=pantryStaples&subCategory=Pasta%20and%20Noodles">Pasta and Noodles</a>
                                <a href="backend/category.php?category=pantryStaples&subCategory=Oils">Oils</a>
                                <a href="backend/category.php?category=pantryStaples&subCategory=Sauces%20and%20Condiments">Sauces and Condiments</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a href="#">Beverages</a>
                            <div class="submenu">
                                <a href="backend/category.php?category=Beverages&subCategory=Water">Water</a>
                                <a href="backend/category.php?category=Beverages&subCategory=Juices">Juices</a>
                                <a href="backend/category.php?category=Beverages&subCategory=Soft%20Drinks">Soft Drinks</a>
                                <a href="backend/category.php?category=Beverages&subCategory=Coffee%20and%20Tea">Coffee and Tea</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a href="#">Snacks and Sweets</a>
                            <div class="submenu">
                                <a href="backend/category.php?category=snacksSweets&subCategory=Chips%20and%20Crackers">Chips and Crackers</a>
                                <a href="backend/category.php?category=snacksSweets&subCategory=Chocolates%20and%20Candies">Chocolates and Candies</a>
                                <a href="backend/category.php?category=snacksSweets&subCategory=Nuts%20and%20Seeds">Nuts and Seeds</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a href="#">Personal Care</a>
                            <div class="submenu">
                                <a href="backend/ategory.php?category=personalCare&subCategory=Toiletries">Toiletries</a>
                                <a href="backend/category.php?category=personalCare&subCategory=Skincare">Skincare</a>
                                <a href="backend/category.php?category=personalCare&subCategory=Haircare">Haircare</a>
                                <a href="backend/category.php?category=personalCare&subCategory=Oral%20Care">Oral Care</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a href="#">Household</a>
                            <div class="submenu">
                                <a href="backend/category.php?category=Household&subCategory=Cleaning%20Supplies">Cleaning Supplies</a>
                                <a href="backend/category.php?category=Household&subCategory=Paper%20Products%20(Toilet%20Paper,%20Paper%20Towels)">Paper Products (Toilet Paper, Paper Towels)</a>
                                <a href="backend/category.php?category=Household&subCategory=Laundry%20Supplies">Laundry Supplies</a>
                                <a href="backend/category.php?category=Household&subCategory=Dishwashing%20Supplies">Dishwashing Supplies</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a href="#">Baby Products</a>
                            <div class="submenu">
                                <a href="backend/category.php?category=babyProducts&subCategory=Baby%20Food">Baby Food</a>
                                <a href="backend/category.php?category=babyProducts&subCategory=Diapers">Diapers</a>
                                <a href="backend/category.php?category=babyProducts&subCategory=Baby%20Wipes">Baby Wipes</a>
                                <a href="backend/category.php?category=babyProducts&subCategory=Baby%20Care">Baby Care</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            
            
        </div>
    </header>
    <br><br><br><br><br><br>
    <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active" data-bs-interval="10000">
            <img src="banner.png" class="d-block w-100" alt="...">
            <!-- Button container inside carousel-item -->
            <div class="carousel-button-container">
              <a href="backend/product.php" class="btn btn-primary">Explor Products</a>
            </div>
          </div>
        </div>
      </div>


      <div class="container-fluid py-5">
        <div class="container py-5">
            <!-- New Products Section -->
            <h1 class="mb-4">New Products</h1>
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="row g-4">
                        <?php if (!empty($newProducts)): ?>
                            <?php foreach ($newProducts as $product): ?>
                                <div class="col-md-6 col-lg-6 col-xl-4">
                                    <div class="rounded position-relative product-item">
                                        <div class="mt-2">
                                            <h5 style="margin-left:5px;">
                                                <?php echo htmlspecialchars($product['storeName']); ?>
                                                <?php if ($product['storeLogo']): ?>
                                                    <img src="../Seller/backend/<?php echo htmlspecialchars($product['storeLogo']); ?>" alt="Store Logo" style="width: 50px; height: 50px; object-fit: cover;">
                                                <?php endif; ?>
                                            </h5>
                                        </div>
                                        <div class="product-img">
                                            <img src="../Seller/backend/product/<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid w-100 rounded-top" alt="<?php echo htmlspecialchars($product['productName']); ?>">
                                        </div>
                                        <div class="p-4 rounded-bottom">
                                            <h4><?php echo htmlspecialchars($product['productName']); ?></h4>
                                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                                            <div class="d-flex justify-content-between flex-lg-wrap">
                                                <p class="text-dark fs-5 fw-bold mb-0">Rs <?php echo htmlspecialchars($product['price']); ?></p>
                                                <a href="#" class="btn btn-custom add-to-cart" data-product-id="<?php echo $product['productID']; ?>"><i class="fa fa-shopping-cart cart"></i> Add to cart</a>
                                                <a href="productView.php?product_id=<?php echo $product['productID']; ?>">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No new products available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Discount Promotion Products Section -->
            <h1 class="mt-5 mb-4">Discount Promotion Products</h1>
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="row g-4">
                        <?php if (!empty($discountProducts)): ?>
                            <?php foreach ($discountProducts as $product): ?>
                                <div class="col-md-6 col-lg-6 col-xl-4">
                                    <div class="rounded position-relative product-item">
                                        <div class="mt-2">
                                            <h5 style="margin-left:5px;">
                                                <?php echo htmlspecialchars($product['storeName']); ?>
                                                <?php if ($product['storeLogo']): ?>
                                                    <img src="../Seller/backend/<?php echo htmlspecialchars($product['storeLogo']); ?>" alt="Store Logo" style="width: 50px; height: 50px; object-fit: cover;">
                                                <?php endif; ?>
                                            </h5>
                                        </div>
                                        <div class="product-img">
                                            <img src="../Seller/backend/product/<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid w-100 rounded-top" alt="<?php echo htmlspecialchars($product['productName']); ?>">
                                        </div>
                                        <div class="p-4 rounded-bottom">
                                            <h4><?php echo htmlspecialchars($product['productName']); ?></h4>
                                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                                            <div class="d-flex justify-content-between flex-lg-wrap">
                                                <?php 
                                                $originalPrice = $product['price'];
                                                $discountedPrice = $originalPrice * (1 - $product['discount'] / 100); 
                                                ?>
                                                <!-- Display Original Price with Strikethrough if Discounted -->
                                                <?php if ($product['discount'] > 0): ?>
                                                    <p class="text-muted fs-6 mb-0" style="text-decoration: line-through;">Rs <?php echo htmlspecialchars($originalPrice); ?></p>
                                                <?php endif; ?>
                                                
                                                <!-- Display Discounted Price -->
                                                <p class="text-dark fs-5 fw-bold mb-0">
                                                    Rs <?php echo htmlspecialchars($discountedPrice); ?>
                                                </p>

                                                <!-- Add to Cart Button -->
                                                <a href="#" class="btn btn-custom add-to-cart" data-product-id="<?php echo $product['productID']; ?>">
                                                    <i class="fa fa-shopping-cart cart"></i> Add to cart
                                                </a>
                                                
                                                <!-- View Details Link -->
                                                <a href="productView.php?product_id=<?php echo $product['productID']; ?>">View Details</a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No discount promotion products available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <footer>
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
            <div class="container py-5">
                <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
                    <div class="row g-4">
                        <div class="col-lg-3">
                            <a href="#">
                                <h1 class="text-primary mb-0">Fruitables</h1>
                                <p class="text-secondary mb-0">Fresh products</p>
                            </a>
                        </div>
                        <div class="col-lg-6">
                            <div class="position-relative mx-auto">
                                <input class="form-control border-0 w-100 py-3 px-4 rounded-pill" type="number" placeholder="Your Email">
                                <button type="submit" class="btn btn-primary border-0 border-secondary py-3 px-4 position-absolute rounded-pill text-white" style="top: 0; right: 0;">Subscribe Now</button>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="d-flex justify-content-end pt-3">
                                <a class="btn  btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-youtube"></i></a>
                                <a class="btn btn-outline-secondary btn-md-square rounded-circle" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-light mb-3">Why People Like us!</h4>
                            <p class="mb-4">typesetting, remaining essentially unchanged. It was
                                popularised in the 1960s with the like Aldus PageMaker including of Lorem Ipsum.</p>
                            <a href="" class="btn border-secondary py-2 px-4 rounded-pill text-primary">Read More</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex flex-column text-start footer-item">
                            <h4 class="text-light mb-3">Shop Info</h4>
                            <a class="btn-link" href="">About Us</a>
                            <a class="btn-link" href="">Contact Us</a>
                            <a class="btn-link" href="">Privacy Policy</a>
                            <a class="btn-link" href="">Terms & Condition</a>
                            <a class="btn-link" href="">Return Policy</a>
                            <a class="btn-link" href="">FAQs & Help</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex flex-column text-start footer-item">
                            <h4 class="text-light mb-3">Account</h4>
                            <a class="btn-link" href="">My Account</a>
                            <a class="btn-link" href="">Shop details</a>
                            <a class="btn-link" href="">Shopping Cart</a>
                            <a class="btn-link" href="">Wishlist</a>
                            <a class="btn-link" href="">Order History</a>
                            <a class="btn-link" href="">International Orders</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-light mb-3">Contact</h4>
                            <p>Address: 1429 Netus Rd, NY 48247</p>
                            <p>Email: Example@gmail.com</p>
                            <p>Phone: +0123 4567 8910</p>
                            <p>Payment Accepted</p>
                            <img src="img/payment.png" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

   

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
</body>

</html>

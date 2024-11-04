<?php
session_start();
include('dbConnection.php');


$category = $_GET['category'];
$subCategory = $_GET['subCategory'];

$sql = "SELECT * FROM product WHERE category='$category' AND subCategory='$subCategory'";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trolly Master</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/home.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"><!--fontawesome-free-5.15.4-web-->
   <!--fontawesome-free-5.15.4-web-->
    <link rel="stylesheet" href="../../css/nevigation.css">
    <link rel="stylesheet" href="../../css/nav2style.css">
    <script src="../../js/bootstrap.min.js"></script>
  
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
                    <form class="search-form ms-auto" action="productSearch.php" method="get">
                        <input type="text" name="search" placeholder="Search...">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.html">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="product.php">All Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="viewCart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../index.html" id="loginBtn"><i class="fas fa-sign-out-alt"></i> Log Out</a>
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
                                <a href="../category.php?category=fruitsVegetables&subCategory=Fresh%20Fruits">Fresh Fruits</a>
                                <a href="../category.php?category=fruitsVegetables&subCategory=Fresh%20Vegetables">Fresh Vegetables</a>
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

    <div class="container-fluid py-5">
        <div class="container py-5">
            <h1 class="mb-4">Products</h1>
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="row g-4">
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                            <div class="col-md-6 col-lg-6 col-xl-4">
                                <div class="rounded position-relative product-item">
                                    <div class="product-img">
                                        <img src="../../Seller/backend/product/<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid w-100 rounded-top" alt="<?php echo htmlspecialchars($product['productName']); ?>">
                                    </div>
                                    <div class="p-4 p-4 rounded-bottom">
                                        <h4><?php echo $product['productName']; ?> <?php echo $product['quantity'] , $product['scale']; ?></h4>
                                        <p><?php echo $product['description']; ?></p>
                                        <p>Unit Price: LKR <?php echo $product['unitPrice']; ?> Per <?php echo $product['scale'] == 'l' ? 'ml' : ($product['scale'] == 'kg' ? 'g' : $product['scale']);?> </p>
                                        <div class="d-flex justify-content-between flex-lg-wrap">
                                            <p class="text-dark fs-5 fw-bold mb-0">Rs <?php echo $product['price']; ?> </p>
                                            <a href="cart.php?productID=<?php echo $product['productID']; ?>" class="btn btn-custom add-to-cart" ><i class="fa fa-shopping-cart cart"></i> Add to cart</a>
                                            <a href="productView.php?product_id=<?php echo $product['productID']; ?>">View Details</a>
                                        </div>
                            </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No products available.</p>
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
                            <a class="btn-link" href="">Shopping List</a>
                            <a class="btn-link" href="">Order History</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-light mb-3">Contact</h4>
                            <p>Email: trollymaster@gmail.com</p>
                            <p>Phone: +0123 4567 8910</p>
                            <p>Payment Accepted</p>
                            <img src="img/payment.png" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

     <!-- login script -->
     <script>
            document.getElementById('loginBtn').onclick = function () {
                document.getElementById('popup').classList.add('show');
            };

            document.getElementById('closeBtn').onclick = function () {
                document.getElementById('popup').classList.remove('show');
            };

            window.onclick = function (event) {
                if (event.target == document.getElementById('popup')) {
                    document.getElementById('popup').classList.remove('show');
                }
            };

            function selectAccountType(accountType) {
                document.getElementById('accountTypeInput').value = accountType;
                document.getElementById('accountTypeForm').submit();
            }
        </script>

        <!-- Bootstrap JS, Popper.js, and jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>

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

    </body>
</body>
</html>

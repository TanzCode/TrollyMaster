<?php 
session_start();
include('conn.php');

// Retrieve the search keyword from the GET request
$search = $_GET['search'];

// Build the query with a LIKE clause to find similar product names
$query = "SELECT * FROM product WHERE productName LIKE ?";

// Prepare the statement
$stmt = $conn->prepare($query);

// Bind the parameter for the search keyword
$searchParam = "%" . $search . "%";
$stmt->bind_param('s', $searchParam);

$stmt->execute();
$result = $stmt->get_result();

// Include CSS for styling the cards
echo "<style>
    .cards-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }
    .product-item {
        border: 1px solid darkorange;
        margin-bottom: 20px;
        background: #fff;
        border-radius: 5px;
        overflow: hidden;
        transition: transform 0.2s;
        width: 300px;
        padding: 15px;
        box-sizing: border-box;
    }
    .product-item:hover {
        transform: scale(1.05);
    }
    .product-img img {
        max-width: 100%;
        border-radius: 5px 5px 0 0;
    }
    .product-item h4 {
        font-size: 18px;
        margin-bottom: 10px;
    }
    .product-item p {
        font-size: 14px;
        color: #6c757d;
    }
    .d-flex {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .btn-custom {
        color: #4CAF50;
        background-color: #ffffff;
        border: 1px solid darkorange;
        border-radius: 20px;
        padding: 10px 20px;
        text-align: center;
        transition: background-color 0.3s, color 0.3s, border-color 0.3s;
    }
    .btn-custom:hover {
        background-color: darkorange;
        color: #ffffff;
        border-color: darkorange;
    }
</style>";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Basket - Welcome Home</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/home.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"><!--fontawesome-free-5.15.4-web-->
   <!--fontawesome-free-5.15.4-web-->
    <link rel="stylesheet" href="../../assets/css/nevigation.css">
    <link rel="stylesheet" href="../../assets/css/nav2style.css">
    <script src="../../assets/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/style.css">

</head>
<body>
<header id="header" class="header fixed-top">
    <div>
<nav class="navbar navbar-expand-lg navbar-light">
   
    <a class="navbar-brand" href="index.html">
        <img src="logo.png" alt="Logo">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <form class="search-form ms-auto" action="searchProducts.php" method="get">
            <input type="text" placeholder="Search..." name="search" id="search">
            <button type="submit"><i class="fas fa-search" ></i></button>
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
                <a class="nav-link" href="#"><i class="fas fa-sign-out-alt"></i> Login</a>
            </li>
        </ul>
    </div>
</nav>
<nav class="navbar2">

         <div class="container">
              <ul class="list">
                  <li class="dropdown">
                      <a href="#">Fruits and Vegetables</a>
                      <div class="submenu">
                          <a href="#">Fresh Fruits</a>
                          <a href="#">Fresh Vegetables</a>
                      </div>
                  </li>
                  <li class="dropdown">
                      <a href="#">Dairy </a>
                      <div class="submenu">
                          <a href="#">Milk</a>
                          <a href="#">cheese</a>
                          <a href="#">Yogurt</a>
                          <a href="#">Butter</a>
                          <a href="#">Eggs</a>
                      </div>
                  </li>
                  <li class="dropdown">
                      <a href="#">Pantry Staples</a>
                      <div class="submenu">
                          <a href="#">Rice and Grains</a>
                          <a href="#">Pasta and Noodles</a>
                          <a href="#">Oils</a>
                          <a href="#">Sauces and Condiments</a>
                      </div>
                  </li>
                  <li class="dropdown">
                      <a href="#">Beverages</a>
                      <div class="submenu">
                          <a href="#">Water</a>
                          <a href="#">Juices</a>
                          <a href="#">Soft Drinks</a>
                          <a href="#">Coffee and Tea</a>
                      </div>
                  </li>
                  <li class="dropdown">
                      <a href="#">Snacks and Sweets</a>
                      <div class="submenu">
                          <a href="#">Chips and Crackers</a>
                          <a href="#">Chocolates and Candies</a>
                          <a href="#">Nuts and Seeds</a>
                      </div>
                  </li>
                  <li class="dropdown">
                      <a href="#">Personal care</a>
                      <div class="submenu">
                          <a href="#">Toiletries</a>
                          <a href="#">Skincare</a>
                          <a href="#">Haircare</a>
                          <a href="#">Oral care</a>
                      </div>
                  </li>
                  <li class="dropdown">
                      <a href="#">Household </a>
                      <div class="submenu">
                          <a href="#">Cleaning Supplies</a>
                          <a href="#">Paper Products (Toilet Paper, Paper Towels)</a>
                          <a href="#">Laundry Supplies</a>
                          <a href="#">Dishwashing Supplies</a>
                      </div>
                  </li>
                  <li class="dropdown">
                      <a href="#">Baby products </a>
                      <div class="submenu">
                          <a href="#">Baby Food</a>
                          <a href="#">Diapers</a>
                          <a href="#">Baby Wipes</a>
                          <a href="#">Baby care</a>
                      </div>
                  </li>

             </ul>
        </nav>
</div>
</header>
<br>
<br>
<br>
<br><br>
<br>
<div>
<?php 
// Check if any products were found
if ($result->num_rows > 0) {
    echo "<h2>Products</h2>";
    echo "<div class='cards-container'>";

    while($row = $result->fetch_assoc()) {
        echo "<div class='product-item'>
                <div class='product-img'>
                    <img src='../../Admin/productManagement/" . htmlspecialchars($row['image']) . "' alt='Product Image'>
                </div>
                <h4>" . htmlspecialchars($row['productName']) . "</h4>
                <p><strong>Category:</strong> " . htmlspecialchars($row['category']) . "</p>
                <p><strong>Sub Category:</strong> " . htmlspecialchars($row['subCategory']) . "</p>
                <p><strong>Description:</strong> " . htmlspecialchars($row['description']) . "</p>
                <p><strong>Brand:</strong> " . htmlspecialchars($row['brand']) . "</p>
                <p><strong>Storage Requirements:</strong> " . htmlspecialchars($row['storageRequirements']) . "</p>
                <p><strong>Discounts:</strong> " . htmlspecialchars($row['discounts']) . "</p>
                <p><strong>Special Details:</strong> " . htmlspecialchars($row['specialDetails']) . "</p>
                <p><strong>Stock Amount:</strong> " . htmlspecialchars($row['stockAmount']) . "</p>
                <p><strong>Price:</strong> " . htmlspecialchars($row['price']) . "</p>
                <div class='d-flex'>
                    <button class='btn btn-custom'>Buy Now</button>
                </div>
            </div>";
    }
    echo "</div>";
} else {
    echo "No products found matching the search criteria.";
}

$stmt->close();

?>

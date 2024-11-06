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
    <link href="style.css" rel="stylesheet">
    <link href="styleproductInsert.css" rel="stylesheet">
    <title>Dashboard - Seller | Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            margin-left:5%;
            width: 100%;
            border-collapse: collapse;
            /* margin: 20px 0; */
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #81C408;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        img {
            display: block;
            margin: 0 auto;
            width: 100px; /* Fixed size for images */
            height: auto; /* Maintain aspect ratio */
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons button {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }

        .edit-button {
            background-color: #4CAF50;
            color: white;
        }

        .delete-button {
            background-color: #f44336;
            color: white;
        }
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
        <h1>Product List</h1>
    <table>
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Sub-Category</th>
            <th>Description</th>
            <th>Brand</th>
            <th>Storage Requirements</th>
            <th>Discounts</th>
            <th>Special Details</th>
            <th>Stock Amount</th>
            <th>Price</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php
        include 'dbConnection.php';
        $sql = "SELECT * FROM product where sellerID=$userID ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['productID'] . "</td>";
                echo "<td>" . $row['productName'] . "</td>";
                echo "<td>" . $row['category'] . "</td>";
                echo "<td>" . $row['subCategory'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                echo "<td>" . $row['brand'] . "</td>";
                echo "<td>" . $row['storageRequirements'] . "</td>";
                echo "<td>" . $row['discounts'] . "</td>";
                echo "<td>" . $row['specialDetails'] . "</td>";
                echo "<td>" . $row['stockAmount'] . "</td>";
                echo "<td>" . $row['price'] . "</td>";
                $id = $row['productID'];

                echo "<td><img src='" . $row['image'] . "' alt='Product Image'></td>";
                echo "<td class='action-buttons'>";
                echo "<a href='editProduct.php?id=" . $row['productID'] . "'><button class='edit-button'>Edit</button></a>";
                echo "<a href='deleteProduct.php?id=" . $row['productID'] . "' onclick=\"return confirm('Are you sure you want to delete this product?');\"><button class='delete-button'>Delete</button></a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='13'>No products found</td></tr>";
        }
        ?>
    </table>

    <script src="script.js"></script>
    <!--Container Main end-->
    <script src="../JS/slider.js"></script>
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

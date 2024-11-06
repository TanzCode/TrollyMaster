<?php
session_start();
include('dbConnection.php');

// Check if the first name is set in the session
if (isset($_SESSION['fName']) && isset($_SESSION['lName']) && isset($_SESSION['sellerID'])) {
    $firstName = $_SESSION['fName'];
    $lastName = $_SESSION['lName'];
    $userID = $_SESSION['sellerID'];
   
    
} else {
    echo "First name not set.";
}

$search = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}

$sql = "SELECT 
            cart.cartItemID,
            cart.cusID,
            cart.productID,
            cart.quantity,
            cart.price,
            cart.discount,
            cart.status,
            cart.createdAt,
            cart.orderID,
            cart.orderStatus,
            customer_personal.fName,
            customer_personal.email,
            customer_personal.phone,
            customer_personal.cusID,
            customer_personal.streetAddress,
            customer_personal.city,
            customer_personal.province,
            customer_personal.postalCode,
            product.productName,
            product.sellerID,
            seller_personal.sellerID
            
        FROM 
            cart
        JOIN 
            customer_personal ON cart.cusID = customer_personal.cusID
        JOIN 
            product ON cart.productID = product.productID
        JOIN 
            seller_personal ON product.sellerID = seller_personal.sellerID
        LEFT JOIN 
            orders ON cart.orderID = orders.orderID
        WHERE 
            product.sellerID = ? AND (customer_personal.fName LIKE ? OR product.productName LIKE ?)
        ORDER BY 
            cart.cartItemID DESC";

$stmt = $conn->prepare($sql);
$searchParam = "%$search%";
$stmt->bind_param('iss', $userID, $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="CSS/slider.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <style>
        
        .complete-btn {
            background-color: grey; /* Default color for disabled state */
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: not-allowed;
        }

        .complete-btn:enabled {
            background-color: green; /* Green color when enabled */
            cursor: pointer;
        }
        h1 {
            text-align: center;
            color: #333;
        }

        .search-container {
            margin: 0 auto 20px;
            margin-bottom: 20px;
            text-align: center;
            width: 400px;
            text-align: center;
        }

        .search-container input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-container button {
            padding: 10px 15px;
            border: none;
            background-color: #81C408;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #6DAE00;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
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
            border-radius: 5px;
        }

        .delete-button {
            background-color: #f44336;
            color: white;
            border-radius: 5px;
        }
    </style> 
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
                <a href="overview.php" class="nav_logo">
                    <i class='bx bx-layer nav_logo-icon'></i>
                    <span class="nav_logo-name">Trolly Master</span>
                </a>
                <div class="nav_list">
                    <a href="overview.php" class="nav_link" >
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
                    <a href="orderManagement.php"  class="nav_link"  class="nav_link active">
                        <i class='bx bxs-package' ></i><span class="nav_name">Orders</span>
                    </a>

                    <a href="shopManagement.php" class="nav_link">
                        <i class='bx bx-store'></i><span class="nav_name">Shop management</span>
                    </a>
                    <a href="promotions.php" class="nav_link">
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
    <!--Container Main start-->
    <div class="height-100 bg-light">
        <div class="search-container">
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Search by Customer Name or Product Name" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

        <table>
            <tr>
                <th>Cart item ID</th>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Order Status</th>
                <th>Order Date</th>
                <th>Customer Email</th>
                <th>Customer Phone</th>
                <th>Customer Address</th>
                <th>Actions</th>
            </tr>
            
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['cartItemID']; ?></td>
                    <td><?php echo $row['orderID']; ?></td>
                    <td><?php echo htmlspecialchars($row['fName']) . ' (' . htmlspecialchars($row['cusID']) . ')'; ?></td>
                    <td><?php echo htmlspecialchars($row['productName']); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                    <td><?php echo htmlspecialchars($row['discount']); ?></td>
                    <td>
                    <?php echo htmlspecialchars($row['orderStatus']); ?>
                            
                            
                    </td>
                    <td><?php echo htmlspecialchars($row['createdAt']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['streetAddress']); ?><br>
                        <?php echo htmlspecialchars($row['city']); ?><br>
                        <?php echo htmlspecialchars($row['province']); ?><br>
                        <?php echo htmlspecialchars($row['postalCode']); ?>
                    </td>
                    <td>
                    <form method="POST" action="updateOrderstatus.php">
                    <input type="hidden" name="cartItemID" value="<?php echo htmlspecialchars($row['cartItemID']); ?>">
                    <?php $orderStatus = htmlspecialchars($row['orderStatus']);  ?>
                    <button type="submit" name="updateOrder" class="complete-btn" <?php echo ($orderStatus === "Processing") ? "" : "disabled"; ?>>Dispatched</button>

                        </form>
                    </td>
                </tr>
                
            <?php endwhile; ?>
        </table>
    </div>
    <!--Container Main end-->
    <script src="JS/slider.js"></script>
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

 
// Handle updating the order status

</body>
</html>
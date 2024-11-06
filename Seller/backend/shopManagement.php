<?php
session_start();

// Check if the first name is set in the session
if (isset($_SESSION['fName']) && isset($_SESSION['lName']) && isset($_SESSION['sellerID'])) {
    $firstName = $_SESSION['fName'];
    $lastName = $_SESSION['lName'];
    $sellerID = $_SESSION['sellerID'];
   
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
    <link href="CSS/slider.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
   <style>
    form {
        background-color: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        margin: 2rem auto;
    }

    h2, h3 {
        color: #28a745;
        font-weight: 600;
        margin-bottom: 1rem;
        text-align: center;
    }

    label {
        display: block;
        font-weight: 500;
        margin: 1rem 0 0.5rem;
        color: #555;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
        margin-bottom: 1rem;
        font-size: 14px;
    }

    input[type="submit"] {
        background-color: #28a745;
        color: #fff;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s;
        display: block;
        margin: 1rem auto;
    }

    input[type="submit"]:hover {
        background-color: #218838;
    }

    /* Optional - If you want to style the hidden fields differently (usually hidden, so this is just precaution) */
    input[type="hidden"] {
        display: none;
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
                    <a href="orderManagement.php"  class="nav_link">
                        <i class='bx bxs-package' ></i><span class="nav_name">Orders</span>
                    </a>

                    <a href="shopManagement.php" class="nav_link" class="nav_link active">
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
        <div class="welcome-message">
            <h1>Welcome, <?php echo htmlspecialchars($firstName) . " " . htmlspecialchars($lastName); ?>!</h1>
            <p>Seller ID: <span id="user-id"><?php echo htmlspecialchars($sellerID); ?></span></p>
        </div>
        <hr>
    <?php
    include 'dbConnection.php';

    // Get seller and store details (replace 1 with dynamic ID as needed)
     // Placeholder for the sellerID
    $query = "SELECT * FROM seller_personal INNER JOIN store ON seller_personal.sellerID = store.sellerID WHERE seller_personal.sellerID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $sellerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    ?>



    <h2>Seller and Store Details</h2>
    <form action="updateStoreDetails.php" method="POST">
        <h3>Seller Details</h3>
        <input type="hidden" name="sellerID" value="<?php echo $row['sellerID']; ?>">
        <label>First Name:</label>
        <input type="text" name="fName" value="<?php echo $row['fName']; ?>"><br>

        <label>Last Name:</label>
        <input type="text" name="lName" value="<?php echo $row['lName']; ?>"><br>

        <label>Gender:</label>
        <input type="text" name="gender" value="<?php echo $row['gender']; ?>"><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $row['email']; ?>"><br>

        <label>Password:</label>
        <input type="password" name="password" value="<?php echo $row['password']; ?>"><br>

        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo $row['phone']; ?>"><br>

        <label>Address:</label>
        <input type="text" name="perAddress" value="<?php echo $row['perAddress']; ?>"><br>

        <h3>Store Details</h3>
        <input type="hidden" name="regID" value="<?php echo $row['regID']; ?>">
        <label>Store Name:</label>
        <input type="text" name="name" value="<?php echo $row['name']; ?>"><br>

        <label>Street Address:</label>
        <input type="text" name="streetAddress" value="<?php echo $row['streetAddress']; ?>"><br>

        <label>City:</label>
        <input type="text" name="city" value="<?php echo $row['city']; ?>"><br>

        <label>Province:</label>
        <input type="text" name="province" value="<?php echo $row['province']; ?>"><br>

        <label>Postal Code:</label>
        <input type="text" name="postalCode" value="<?php echo $row['postalCode']; ?>"><br>

        <label>Logo URL:</label>
        <input type="text" name="logo" value="<?php echo $row['logo']; ?>"><br>

        <input type="submit" name="update" value="Update">
    </form>


    <!--Container Main end-->
    <script src="JS/slider.js"></script>
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

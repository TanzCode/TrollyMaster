<?php
session_start();
include('dbConnection.php');


if (isset($_SESSION['loggedincus']) && $_SESSION['loggedincus'] == true) {
    $userID = htmlspecialchars($_SESSION['cusID']);
} else {
    echo 'Please login to view Cart.';
    exit;
}


// Check if productID is provided
if (isset($_GET['productID'])) {
    $productID = $_GET['productID'];
 


    // Fetch product details from the database
    $sql = "SELECT * FROM product WHERE productID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo "Product not found!";
        exit;
    }
} else {
    echo "No product selected.";
    exit;
}

// Handle the Add to Cart request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quantity = $_POST['amount'];
     // Assuming the user is logged in and userID is stored in the session

    // Calculate total and discount
    $price = $product['price'];
    $discount = $product['discounts']; 
    $total = $quantity * ($price - $discount);
    $category = $product['category'];

    // Insert the item into the cart table
    $insertSql = "INSERT INTO cart(cusID, productID, productName, price, quantity, discount, total, createdAt, status, productCatogory, orderID, orderStatus) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 0, ?, 0, 'Processing')";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("iisdiids", $userID, $product['productID'], $product['productName'], $price, $quantity, $discount, $total, $category);

    if ($stmt->execute()) {
         // Update the product quantity in the product table
         $newQuantity = $product['stockAmount'] - $quantity;
         $updateSql = "UPDATE product SET stockAmount = ? WHERE productID = ?";
         $updateStmt = $conn->prepare($updateSql);
         $updateStmt->bind_param("ii", $newQuantity, $productID);
         
         if ($updateStmt->execute()) {
             // Redirect to success page
             header("Location: SuccessError/successCart.html");
             exit();
         } else {
             // Redirect to error page if the product quantity update fails
             header("Location: SuccessError/error.html");
             exit();
         }
    } else {
        header("Location: SuccessError\error.html");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cart</title>
    <style>
        /* General body settings */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f3f3f3 0%, #ffffff 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Main container */
        .container {
            background-color: #fff;
            width: 400px;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            text-align: center;
        }

        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.15);
        }

        /* Header styles */
        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        /* Product details */
        .product-details {
            margin-bottom: 20px;
        }

        .product-details h2 {
            font-size: 22px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .product-details p {
            font-size: 16px;
            color: #555;
            margin-bottom: 5px;
        }

        /* Form group */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            font-size: 16px;
            color: #333;
            display: block;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 16px;
            color: #333;
            transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .form-group input:focus {
            border-color: #ff8c00;
            box-shadow: 0 4px 8px rgba(255, 140, 0, 0.3);
            outline: none;
        }

        /* Button styles */
        .btn {
            background-color: #ff8c00;
            color: #fff;
            font-size: 16px;
            padding: 12px 20px;
            border-radius: 30px;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: background-color 0.4s ease, transform 0.4s ease, box-shadow 0.4s ease;
            width: 100%;
            margin-top: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            background-color: #e67e22;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        /* Responsive design */
        @media screen and (max-width: 768px) {
            .container {
                width: 90%;
                padding: 30px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Add to Cart</h1>
    <form method="POST" action="cart.php?productID=<?php echo $product['productID']; ?>">
        <div class="product-details">
            <h2><?php echo $product['productName']; ?></h2>
            <p><?php echo $product['description']; ?></p>
            <p>Price: Rs <?php echo number_format($product['price'], 2); ?></p>
            <p>Quantity Available: <?php echo $product['stockAmount']; ?></p>
        </div>

        <div class="form-group">
            <label for="quantity">Enter Quantity:</label>
            <input type="number" name="amount" id="amount" min="1" max="<?php echo $product['stockAmount']; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Add to Cart</button>

        <a href="product.php"><button type="button" class="btn btn-primary">Back</button></a>
    </form>
</div>
</body>
</html>

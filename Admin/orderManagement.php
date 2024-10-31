<?php
session_start();
include('dbConnection.php');


if (isset($_SESSION['loggedincus']) && $_SESSION['loggedincus'] == true) {
    $userID = htmlspecialchars($_SESSION['cusID']);
} else {
    echo 'Please login to view Orders.';
    header("Location: ../login.html");
 
    exit;
}

// Retrieve order data with customer, product, and seller details
$sql = "SELECT 
            cart.cartItemID,
            cart.cusID,
            cart.productID,
            cart.quantity,
            cart.price,
            cart.discount,
            cart.status,
            cart.createdAt,
            customers.customerName,
            products.productName,
            sellers.sellerName,
            sellers.contactInfo
        FROM 
            cart
        JOIN 
            customers ON cart.cusID = customers.customerID
        JOIN 
            products ON cart.productID = products.productID
        JOIN 
            sellers ON products.sellerID = sellers.sellerID
        ORDER BY 
            cart.createdAt DESC";

$result = $conn->query($sql);

// Display order data
echo "<table border='1'>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Discount</th>
            <th>Status</th>
            <th>Order Date</th>
            <th>Seller Name</th>
            <th>Seller Contact</th>
            <th>Actions</th>
        </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['cartItemID']}</td>
            <td>{$row['customerName']}</td>
            <td>{$row['productName']}</td>
            <td>{$row['quantity']}</td>
            <td>{$row['price']}</td>
            <td>{$row['discount']}</td>
            <td>" . ($row['status'] ? 'Completed' : 'Pending') . "</td>
            <td>{$row['createdAt']}</td>
            <td>{$row['sellerName']}</td>
            <td>{$row['contactInfo']}</td>
            <td>
                <form method='POST' action=''>
                    <input type='hidden' name='cartItemID' value='{$row['cartItemID']}'>
                    <button type='submit' name='complete_order'>Mark as Complete</button>
                </form>
            </td>
        </tr>";
}

echo "</table>";

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_order'])) {
    $cartItemID = $_POST['cartItemID'];
    $update_sql = "UPDATE cart SET status = 1 WHERE cartItemID = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("i", $cartItemID);
    $stmt->execute();

    echo "<p>Order #{$cartItemID} marked as completed.</p>";
    echo "<meta http-equiv='refresh' content='0'>"; // Refresh page to show updated status
}

$conn->close();
?>

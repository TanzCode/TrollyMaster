<?php
session_start();
include('conn.php');

if (isset($_SESSION['adminlogin']) && $_SESSION['adminlogin'] == true) {
    // Admin is logged in
} else {
    echo 'Please login to view Orders.';
    header("Location: ../login.html");
    exit;
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
            customer_personal.cusID,
            product.productName,
            seller_personal.email,
            seller_personal.phone
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
            customer_personal.fName LIKE ? OR product.productName LIKE ?
        ORDER BY 
            cart.cartItemID DESC";

$stmt = $conn->prepare($sql);
$searchParam = "%$search%"; // Prepare the search parameter for LIKE
$stmt->bind_param('ss', $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file for styling -->
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

        .search-container {
            margin-bottom: 20px;
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
</head>
<body>

<div style="margin-top: 20px; margin-left:10px; margin-right:10px;">
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
            <th>Seller Name</th>
            <th>Seller Contact</th>
            <th>Actions</th>
        </tr>
        
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
                // Calculate the time difference in hours
                $createdAt = new DateTime($row['createdAt']);
                $now = new DateTime();
                $interval = $now->diff($createdAt);
                $hoursPassed = $interval->h + ($interval->days * 24);

                // Check if 12 hours have passed
                $disableCancel = $hoursPassed >= 12;
            ?>
            <tr>
                <td><?php echo $row['cartItemID']; ?></td>
                <td><?php echo $row['orderID']; ?></td>
                <td><?php echo htmlspecialchars($row['fName']) . ' (' . htmlspecialchars($row['cusID']) . ')'; ?></td>
                <td><?php echo htmlspecialchars($row['productName']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td><?php echo htmlspecialchars($row['price']); ?></td>
                <td><?php echo htmlspecialchars($row['discount']); ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="cartItemID" value="<?php echo htmlspecialchars($row['cartItemID']); ?>">
                        <select name="orderStatus">
                            <option value="OrderAccepted" <?php if ($row['orderStatus'] == 'OrderAccepted') echo 'selected'; ?>>Order Accepted</option>
                            <option value="Delivering" <?php if ($row['orderStatus'] == 'Delivering') echo 'selected'; ?>>Delivering</option>
                            <option value="Completed" <?php if ($row['orderStatus'] == 'Completed') echo 'selected'; ?>>Completed</option>
                            <option value="Cancel" <?php if ($row['orderStatus'] == 'Cancel') echo 'selected'; ?>>Cancel</option>
                        </select>
                </td>
                <td><?php echo htmlspecialchars($row['createdAt']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td>
                    <button type="submit" name="updateOrder" class="complete-btn">Update Order Status</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateOrder'])) {
    $cartItemID = $_POST['cartItemID'];
    $orderStatus = $_POST['orderStatus'];
    
    $update_sql = "UPDATE cart SET orderStatus = ? WHERE cartItemID = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('si', $orderStatus, $cartItemID);
    
    if ($stmt->execute()) {
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>

</body>
</html>

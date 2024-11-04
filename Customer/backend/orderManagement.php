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

$sql = "SELECT 
            cart.cartItemID,
            cart.cusID,
            cart.productID,
            cart.quantity,
            cart.price,
            cart.discount,
            cart.status ,
            cart.createdAt,
            cart.orderID,
            cart.orderStatus,
            customer_personal.fName ,
            product.productName,
            seller_personal.email ,
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
            cart.cusID = ?
        ORDER BY 
            cart.cartItemID DESC";
            
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID); 
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
         /* Styling for the table and search bar */
         .search-container {
            margin: 20px;
        }

        .search-bar {
            padding: 10px;
            width: 50%;
            border: 1px solid #ddd;
            border-radius: 5px;

        }
       
        

        .search-form input[type="submit"] {
            padding: 10px 20px;
            border: none;
            background-color:#008000;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }

        .search-bar:hover {
            border-color: darkorange;;
        }

        /* Styling for table container */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
            background-color: #f9f9f9;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Table headers */
        th {
            background-color: #3f51b5;
            color: #ffffff;
            padding: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        /* Table rows and cells */
        td, th {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        /* Row hover effect */
        tr:hover {
            background-color: #e8eaf6;
        }

        /* Alternate row colors */
        tr:nth-child(even) {
            background-color: #f1f1f1;
        }

        /* Action button styling */
        button {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }
        
        /* Specific button colors */
        .complete-btn {
            background-color: #4caf50;
            color: white;
        }
        
        .cancel-btn {
            background-color: #f44336;
            color: white;
        }

        /* Hover effect for buttons */
        button:hover {
            opacity: 0.9;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            table, th, td {
                font-size: 14px;
            }
            td, th {
                padding: 10px;
            }
        }
    </style>
    <script>
        // JavaScript function for filtering table rows based on search input
        function searchTable() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let table = document.getElementById("orderTable");
            let rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName("td");
                let match = false;

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j] && cells[j].textContent.toLowerCase().includes(input)) {
                        match = true;
                        break;
                    }
                }

                rows[i].style.display = match ? "" : "none";
            }
        }
    </script>
</head>
<body>

<?php include 'header.php'; ?>


<div class="search-container" style="margin-top: 100px;">
    <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search orders..." class="search-bar">
</div>

<div style="margin-top: 20px; margin-left:10px; margin-right:10px;">
    <table border="1">
        <tr>
            <th>Cart item ID</th>
            <th>Order ID</th>
            
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
                <td><?php echo $row['productName']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['discount']; ?></td>
                <td><?php echo $row['orderStatus']; ?></td>
                <td><?php echo $row['createdAt']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="cartItemID" value="<?php echo $row['cartItemID']; ?>">
                        <input type="hidden" name="orderID" value="<?php echo $row['orderID']; ?>">
                        <button type="submit" name="complete_order" class="complete-btn">Mark as Complete</button>
                        <button type="submit" name="cancel_order" class="cancel-btn" 
                            <?php echo $disableCancel ? 'disabled style="background-color: #555555; color: #cccccc;"' : ''; ?>>
                            Cancel
                        </button>
                 </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartItemID = $_POST['cartItemID'];
    $orderID = $_POST['orderID'];
    
    if (isset($_POST['complete_order'])) {
        $update_sql = "UPDATE cart SET orderStatus = 'Completed' WHERE cartItemID = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $cartItemID);
        $stmt->execute();
        echo "<p>Order #{$cartItemID} marked as completed.</p>";
        $update_sqlOrder = "UPDATE orders SET orderStatus = 'Completed' WHERE orderID = ?";
        $stmtorder = $conn->prepare($update_sqlOrder);
        $stmtorder->bind_param("i", $orderID);
        $stmtorder->execute();
    } elseif (isset($_POST['cancel_order'])) {
        $update_sql = "UPDATE cart SET orderStatus = 'Cancel' WHERE cartItemID = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $cartItemID);
        $stmt->execute();
        echo "<p>Order #{$cartItemID} has been cancelled.</p>";
        $update_sqlOrder = "UPDATE orders SET orderStatus = 'Completed' WHERE orderID = ?";
        $stmtorder = $conn->prepare($update_sqlOrder);
        $stmtorder->bind_param("i", $orderID);
        $stmtorder->execute();
    }
    
    echo "<meta http-equiv='refresh' content='0'>"; // Refresh page to show updated status
}

$conn->close();
?>

</body>
</html>

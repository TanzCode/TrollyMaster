<?php
session_start();
include('conn.php');

// Initialize search query
$searchQuery = '';
if (isset($_POST['search'])) {
    $searchQuery = $_POST['search'];
}

// SQL query to fetch products, with search functionality
$sql = "SELECT * FROM product";
if ($searchQuery) {
    $searchQuery = $conn->real_escape_string($searchQuery);
    $sql .= " WHERE productName LIKE '%$searchQuery%' OR brand LIKE '%$searchQuery%' OR specialDetails LIKE '%$searchQuery%'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
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

        .search-form {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-form input[type="text"] {
            padding: 10px;
            width: 300px;
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

        .search-form input[type="submit"]:hover {
            background-color: darkorange;;
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
            border-radius: 5px;
        }

        .delete-button {
            background-color: #f44336;
            color: white;
            border-radius: 5px;
        }

.search-button {
  border: none;
  background-color:#008000;
  color: white;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
}
.search-button:hover {
  background-color: darkorange;
}
    </style>
</head>
<body>
    <h1>Product List</h1>
    
    <!-- Search Form -->
    <div class="search-form">
        <form method="POST" action="">
            <input type="text" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search by product name, brand, or details">
            <input type="submit" value="Search" >
        </form>
    </div>
    
    <table>
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Image</th>
            <th>Sub-Category</th>
            <th>Brand</th>
            <th>Discounts (%)</th>
            <th>Special Details</th>
            <th>Stock Amount</th>
            <th>Price (Rs)</th>
            <th>Unit Price</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['productID'] . "</td>";
                echo "<td>" . $row['productName'] . "</td>";
                echo "<td><img src='" . $row['image'] . "' alt='Product Image'></td>";
                echo "<td>" . $row['subCategory'] . "</td>";
                echo "<td>" . $row['brand'] . "</td>";
                echo "<td>" . $row['discounts'] . "</td>";
                echo "<td>" . $row['specialDetails'] . "</td>";
                echo "<td>" . $row['stockAmount'] . "</td>";
                echo "<td>" . $row['price'] . "</td>";
                echo "<td>" . $row['unitPrice'] . "</td>";
                echo "<td class='action-buttons'>";
                echo "<a href='editProduct.html?id=" . $row['productID'] . "'><button class='edit-button'>Edit</button></a>";
                echo "<a href='deleteProduct.php?id=" . $row['productID'] . "' onclick=\"return confirm('Are you sure you want to delete this product?');\"><button class='delete-button'>Delete</button></a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='11'>No products found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>

<?php
session_start();
include('conn.php');

// Initialize search query
$searchQuery = '';
if (isset($_POST['search'])) {
    $searchQuery = $_POST['search'];
}

// SQL query to fetch customers, with search functionality
$sql = "SELECT * FROM customer_personal";
if ($searchQuery) {
    $searchQuery = $conn->real_escape_string($searchQuery);
    $sql .= " WHERE fName LIKE '%$searchQuery%' OR lName LIKE '%$searchQuery%' OR email LIKE '%$searchQuery%' OR phone LIKE '%$searchQuery%'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List</title>
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
            background-color: #008000;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-form input[type="submit"]:hover {
            background-color: darkorange;
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

        .search-button {
            border: none;
            background-color: #008000;
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
    <h1>Customer List</h1>
    
    <!-- Search Form -->
    <div class="search-form">
        <form method="POST" action="">
            <input type="text" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search by first name, last name, email, or phone">
            <input type="submit" value="Search">
        </form>
    </div>
    
    <table>
        <tr>
            <th>Customer ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Combine address fields
                $address = $row['streetAddress'] . ', ' . $row['city'] . ', ' . $row['province'] . ', ' . $row['postalCode'];

                echo "<tr>";
                echo "<td>" . $row['cusID'] . "</td>";
                echo "<td>" . $row['fName'] . " " . $row['lName'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['phone'] . "</td>";
                echo "<td>" . $address . "</td>";
                echo "<td class='action-buttons'>";
                //echo "<a href='editCustomer.html?id=" . $row['cusID'] . "'><button class='edit-button'>Edit</button></a>";
                echo "<a href='deleteCustomer.php?id=" . $row['cusID'] . "' onclick=\"return confirm('Are you sure you want to delete this customer?');\"><button class='delete-button'>Delete</button></a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No customers found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>

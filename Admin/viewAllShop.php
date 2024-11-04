<?php
session_start();
include('conn.php');

$sql = "SELECT * FROM store";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store List</title>
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
    <h1>Store List</h1>
    <table>
        <tr>
            <th>Registration ID</th>
            <th>Name</th>
            <th>Street Address</th>
            <th>City</th>
            <th>Province</th>
            <th>Postal Code</th>
            <th>Seller ID</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['regID'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['streetAddress'] . "</td>";
                echo "<td>" . $row['city'] . "</td>";
                echo "<td>" . $row['province'] . "</td>";
                echo "<td>" . $row['postalCode'] . "</td>";
                echo "<td>" . $row['sellerID'] . "</td>";
                echo "<td class='action-buttons'>";
                //echo "<a href='editStore.html?id=" . $row['regID'] . "'><button class='edit-button'>Edit</button></a>";
                echo "<a href='deleteStore.php?id=" . $row['regID'] . "' onclick=\"return confirm('Are you sure you want to delete this store?');\"><button class='delete-button'>Delete</button></a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No stores found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>

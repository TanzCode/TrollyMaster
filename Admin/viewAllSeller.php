<?php
session_start();
include('conn.php');

$sql = "SELECT * FROM seller_personal";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller List</title>
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
            border-radius:5px;
        }

        .delete-button {
            background-color: #f44336;
            color: white;
            border-radius:5px;
        }
    </style>
</head>
<body>
    <h1>Seller List</h1>
    <table>
        <tr>
            <th>Seller ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Gender</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['sellerID'] . "</td>";
                echo "<td>" . $row['fName'] . "</td>";
                echo "<td>" . $row['lName'] . "</td>";
                echo "<td>" . $row['gender'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['phone'] . "</td>";
                echo "<td>" . $row['perAddress'] . "</td>";
                echo "<td class='action-buttons'>";
               // echo "<a href='editSeller.html?id=" . $row['sellerID'] . "'><button class='edit-button'>Edit</button></a>";
                echo "<a href='deleteSeller.php?id=" . $row['sellerID'] . "' onclick=\"return confirm('Are you sure you want to delete this seller?');\"><button class='delete-button'>Delete</button></a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No sellers found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>

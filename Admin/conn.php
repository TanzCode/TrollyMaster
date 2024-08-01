<?php
//-----------Designed by RuchiraLK--------//

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "master_basket"; // Enter your Database name in the MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
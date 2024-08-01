<?php include('conn.php');?>
<?php
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    //users = enter your table name of the database
    $sql = "SELECT * FROM customer_personal WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();
        $firstName = $row['fName'];
        $lastName= $row['lName'];
    
        echo "Login successful, welcome $firstName $lastName";
        ?>
        <h1>Passed</h1>
        <?php
    } else {
        echo "Invalid email or password";
    
        ?>
        <h1>Failed</h1>
        <?php
    }
    
    $conn->close();
    ?>
    
?>
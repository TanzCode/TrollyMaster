<?php include('conn.php');
    session_start();
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    //users = enter your table name of the database
    $sql = "SELECT * FROM seller_personal WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();

        $firstName = $row['fName'];
        $lastName= $row['lName'];
        $userID =$row['sellerID'];
     // Store the first name in the session
     $_SESSION['firstName'] = $firstName;
     $_SESSION['lastName'] = $lastName;
     $_SESSION['userID'] = $userID;

     // Redirect to sellerDashboard.php
     header("Location: sellerDashboard.php");
     exit();
        
    } else {
        echo "Invalid email or password";
    
        ?>
        <h1>Failed</h1>
        <?php
    }
    
    $conn->close();
    ?>
    
?>
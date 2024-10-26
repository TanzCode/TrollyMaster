
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Database connection
    include 'dbConnection.php';

    // Get the input data 
    $email =  $_POST["email"];
    $password = $_POST["password"];

   
    if (!empty($email) && !empty($password)) {

        // SQL query to check if the user exists
        $sql = "SELECT * FROM customer_personal WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        

        if ($result && mysqli_num_rows($result) > 0) 
        {
            // Fetch the user's data
            $row = mysqli_fetch_assoc($result);
            $hash = $row['password'];

            // Verify the password
            if (password_verify($password, $hash)) {
                // Start the session and set session variables
                session_start();
                $_SESSION['loggedincus'] = true;
                $_SESSION['cusID']=$row['cusID'];
                $_SESSION['fname'] = $row['fName'];
                $_SESSION['lname'] = $row['lName'];
                $_SESSION['streetAddress'] = $row['streetAddress'];
                $_SESSION['city'] = $row['city'];
                $_SESSION['province'] = $row['province'];
                $_SESSION['postalCode'] = $row['postalCode'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['phone'] = $row['phone'];


                echo "<h3>Login successful! Welcome, " . $_SESSION['fname'] ." " .$_SESSION['lname']. ".</h3>";
                // Redirect to the passenger dashboard or another page
                header("Location: index.html");
                exit();
            } 
            else 
            {
                echo "<h3>Incorrect password. Please try again.</h3>";
            }
        } 
        else 
        {
            echo "<h3>No account found with that email. Please sign up first.</h3>";
        }
        
        // Close the database connection
        mysqli_close($conn);
    } else 
    {
        echo "<h3>Please fill in both fields.</h3>";
    }
}

?>


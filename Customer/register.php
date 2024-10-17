

<?php

/*
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Function to send email using PHPMailer
function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'citytaxilk.pvtltd@gmail.com';      
        $mail->Password   = 'ekrz uuto juca tgdm';                   
        $mail->SMTPSecure = 'tls';                                  
        $mail->Port       = 587;                                    

        //Recipients
        $mail->setFrom('citytaxilk.pvtltd@gmail.com', 'City Taxi');
        $mail->addAddress($to);                                      // Add a recipient

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);                         // Fallback in case client does not support HTML

        $mail->send();
        echo 'Email has been sent successfully!';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
*/
if($_SERVER["REQUEST_METHOD"] == "POST") {

    //database connection
    include 'dbConnection.php';

    //get the input data
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $conPassword = $_POST['confirm-password'];
    $phone = $_POST['phone'];
    $streetAddress = $_POST['street-address'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $postalCode = $_POST['postal-code'];
    
    //password validation 
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';

   

    //email
   
    /*$subject = 'Welcome to City Taxi - Registration Successful!';
    $body = "
        <html>
        <body>
        <p>Dear $firstName $lastName,</p>
        <p>We are thrilled to welcome you to City Taxi! Your registration has been successfully completed, and you are now ready to start booking rides with us.</p>
        <p>Here are your login details:</p>
        <p>Email: $email<br>Password: $password</p>
        <p>You can log in to your account at any time through our website or mobile app to book a ride, manage your bookings, and view your ride history.</p>
        <p>Thank you for choosing City Taxi, and we look forward to serving you soon!</p>
        <p>Best regards,<br>The City Taxi Team</p>
        </body>
        </html>
    ";

*/
   


    
        if(($password == $conPassword) && preg_match($pattern, $password)){

             // Check if the provided email already exists
                $result = $conn->query("SELECT COUNT(*) AS count FROM customer_personal WHERE email = '$email'");
                $row = $result->fetch_assoc();

            if ($row['count'] > 0) {
                    $_SESSION['error'] = "Email already exists!";
                    echo'email already exist';
                    //header("Location: ../driversSignup.html");
                    exit();
                }


                $hash = password_hash($password, PASSWORD_DEFAULT);


                
                 //customer registration input query
                $sql = "INSERT INTO customer_personal (fName, lName, gender, email, password, phone, streetAddress, city, province, postalCode) VALUES ('$firstName', '$lastName', '$gender', '$email', '$hash', '$phone', '$streetAddress', '$city', '$province', '$postalCode')";

                
                if(mysqli_query($conn, $sql)){

                    //sendEmail($email, $subject, $body);
                    echo "<h3>data stored in the database successfully.</h3>";
                    // Redirect to the passenger dashboard or another page
                    header("Location: SuccessError/success.html");
                    //send email to passenger
                    
                    exit();


                
                }
                else
                {
                    echo( "ERROR : Hush! SORRY $sql. ".mysqli_error($conn));
                    // Redirect to the passenger dashboard or another page
                    header("Location: SuccessError/error.html");
                    exit();
                
                }
                mysqli_close($conn);
            
            
        }
        else{
                echo "<h3>Password confirmation is fail</h3>";
                // Redirect to the passenger dashboard or another page
                header("Location: SuccessError/error.html");
                exit();
            }
    

     
}

?>
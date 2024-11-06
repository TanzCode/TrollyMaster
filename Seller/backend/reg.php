<?php
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
        $mail->Username   = 'trollymaster.lk@gmail.com';      
        $mail->Password   = 'wvlh qfbg xnas zukm';                   
        $mail->SMTPSecure = 'tls';                                  
        $mail->Port       = 587;                                    

        //Recipients
        $mail->setFrom('trollymaster.lk@gmail.com', 'Trolly Master');
        $mail->addAddress($to);                                      

        // Content
        $mail->isHTML(true);                                       
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);                         
        $mail->send();
        echo 'Email has been sent successfully!';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
       


// Include database connection
include 'dbConnection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get data from Personal Details form (Step 2)
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $gender = $_POST['gender'];
    $perNumber = $_POST['perNumber'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confPassword = $_POST['confPassword'];

    // Get data from Shop Details (Step 1) passed through hidden fields
   // $regID = $_POST['regID'];
    $storeName = $_POST['storeName'];
    $streetAddress = $_POST['streetAddress'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $postalCode = $_POST['postalCode'];

     // Handle file upload 
    // Image
   
  // Handle image upload
    $targetDir = "uploadlogo/";
    $fileName = isset($_FILES["productImage"]["name"]) ? basename($_FILES["productImage"]["name"]) : '';
    $targetFilePath = $targetDir . $fileName;

    // Check if the file is uploaded and move it to the target directory
    if (!empty($fileName) && move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFilePath)) {
        $picture = $targetFilePath;
    } else {
        $picture = ""; // Set to empty if no file is uploaded
    }



     //password validation 
     $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';
     if (!preg_match($pattern, $password)) {
         $_SESSION['error'] = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, and one number.";
         //header("Location: ../driversSignup.html");
         exit();
     }

    // Validate that passwords match
    if ($password !== $confPassword) {
        header("Location: SuccessError/error.html");
        exit();
    }
    // Check if the provided email already exists
    $result = $conn->query("SELECT COUNT(*) AS count FROM seller_personal WHERE email = '$email'");
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        $_SESSION['error'] = "Email already exists!";
        //echo'email already exist';
        //header("Location: ../driversSignup.html");
        exit();
    }
        

    // Hash the password for security
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert Personal Details into the database
    $sqlPersonal = "INSERT INTO seller_personal (fName, lName, gender, phone, perAddress, email, password) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmtPersonal = $conn->prepare($sqlPersonal);
    $stmtPersonal->bind_param("sssssss", $firstName, $lastName, $gender, $perNumber, $address, $email, $hashPassword);

    // Execute the seller_personal insertion
    if ($stmtPersonal->execute()) {
        // Get the last inserted ID (seller_personal ID)
        $sellerID = $conn->insert_id;

        // Insert Shop Details into the store table, including the seller_personal ID
        $stmtShop = $conn->prepare("INSERT INTO store ( name, streetAddress, city, province, postalCode,sellerID, logo) 
                    VALUES ('$storeName', '$streetAddress', '$city', '$province', '$postalCode', '$sellerID','$picture')");
        

        // Execute the store insertion
        if ($stmtShop->execute()) {
            echo "Registration successful!";
            $subject = "Welcome to Trolly Master!";
            $body = "
                <html>
                <body>
                <p>Dear $firstName $lastName,</p>
                <p>Congratulations and welcome to Trolly Master! Your registration has been successfully processed.</p>
                <p><strong>Username:</strong> $email<br><strong>Password:</strong> $password</p>
                <p>Best regards,<br>The Trolly Master team</p>
                <p>Contact us: <a href='mailto:trollymaster.lk@gmail.com'>citytaxilk.pvtltd@gmail.com</a> | +94 123 456 789</p>
                </body>
                </html>
            ";
           sendEmail($email, $subject, $body);
            header("Location: SuccessError/success.html");
        } else {
            echo "Error: " . $stmtShop->error;
            header("Location: SuccessError/error.html");
        }

        // Close the Shop statement
        $stmtShop->close();
    } else {
        echo "Error: " . $stmtPersonal->error;
        header("Location: SuccessError/error.html");
    }

    // Close the Personal statement and connection
    $stmtPersonal->close();
    $conn->close();
} else {
    echo "Invalid request method!";
    header("Location: SuccessError/error.html");
}
?>

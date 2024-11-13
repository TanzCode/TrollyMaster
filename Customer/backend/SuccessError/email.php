
<?php

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

// Define the subject and body for the order confirmation email
$subject = "Order Confirmation from Trolly Master";

$body = "
<html>
<body>
    <p>Dear $firstName $lastName,</p>
    <p>Thank you for shopping with Trolly Master! Your order has been placed successfully and is being processed. You can expect your delivery to arrive in 7-8 business days.</p>

    <h3>Order Details:</h3>
    <p><strong>Order ID:</strong> $orderID</p>
    <p><strong>Grand Total:</strong> $grandTotal</p>
    
    <h4>Shipping Details:</h4>
    <p><strong>Delivery Address:</strong> $address</p>
    <p><strong>Estimated Delivery Time:</strong> 7-8 business days</p>

    <h4>Order Summary:</h4>
    <ul>
        <!-- Loop through the ordered items and list them -->
        $orderItemsHtml
    </ul>

    <p>Thank you for choosing Trolly Master. We look forward to serving you again!</p>
    <p>If you have any questions about your order, feel free to contact us at <a href='mailto:trollymaster.lk@gmail.com'>trollymaster.lk@gmail.com</a> or call +94 123 456 789.</p>

    <p>Best regards,<br>The Trolly Master Team</p>
</body>
</html>
";

sendEmail($email, $subject, $body);

?>
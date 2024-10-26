<?php 
session_start();
require __DIR__ . "/vendor/autoload.php"; 

if (isset($_GET['grandTotal']) && isset($_SESSION['cusID'])) {
    $grandTotal = htmlspecialchars($_GET['grandTotal']); // Sanitize grandTotal input
    $_SESSION['grandTotal'] = $grandTotal; // Store grandTotal in session if needed
    $cusID = $_SESSION['cusID']; // Retrieve the customer ID from session
} else {
    $grandTotal = '0'; // Default value if grandTotal is not set
    $cusID = null; // Set to null if cusID is not in session
}

    // Convert fare to integer for Stripe (Stripe expects the amount in cents/paisa, so multiply by 100)
    $fareInCents = intval(floatval($grandTotal) * 100);

    //require __DIR__ . "/vendor/autoload.php";

    $stripe_secret_key = "sk_test_51QDmKXRvgO2B7WnsDkff75tuhmJE0e2RNITeFJAAS08SpjNLbnNk5gKDqiL6cf2DHk21036nal0UBlEw2KuhOJm700O98Ch7bb";

    \Stripe\Stripe::setApiKey($stripe_secret_key);

    $checkout_session = \Stripe\Checkout\Session::create([
        "mode" => "payment",
        "success_url" => "http://localhost/TrollyMaster/Customer/backend/SuccessError/successPayment.php",
        "cancel_url" => "http://localhost/customer/backend/viewCart.php",
        "locale" => "auto",
        "line_items" => [
            [
                "quantity" => 1,
                "price_data" => [
                    "currency" => "lkr",
                    "unit_amount" => $fareInCents, // Pass the fare amount in cents/paisa
                    "product_data" => [
                        "name" => "Checkout your Order",
                        "images" => [
                            "logo.png"
                        ],
                    ]
                ]
            ],        
        ]
    ]);

    http_response_code(303);
    header("Location: " . $checkout_session->url);
?>

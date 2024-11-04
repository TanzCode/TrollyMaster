<?php
session_start();
include 'dbConnection.php';

// Check if the user is logged in
if (!isset($_SESSION['cusID'])) {
    header('Location: ../login.html'); // Redirect to login if not logged in
    exit();
}

$cusID = mysqli_real_escape_string($conn, $_SESSION['cusID']); // Secure cusID
$query = "SELECT * FROM cart WHERE cusID = $cusID AND status = 0";
$result = mysqli_query($conn, $query);

// Fetch remaining budget from budget table for this user
$budgetQuery = "SELECT remainingBudget FROM expenses WHERE cusID = $cusID";
$budgetResult = mysqli_query($conn, $budgetQuery);
$remainingBudget = 0;

if ($budgetResult && mysqli_num_rows($budgetResult) > 0) {
    $budgetRow = mysqli_fetch_assoc($budgetResult);
    $remainingBudget = $budgetRow['remainingBudget'];
}
?>

<?php
include 'header.php';
?>

<div class="container" style="margin-top: 120px;">
    <h2>Your Cart</h2>
    <input type="hidden" id="remainingBudget" value="<?php echo $remainingBudget; ?>">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="cartItems">
            <!-- Cart items will be dynamically loaded here -->
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right"><strong>Grand Total:</strong></td>
                <td id="grandTotal" name="grandTotal">0</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <button id="checkoutButton" class="btn btn-success">Proceed to Checkout</button>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    // Load cart items on page load
    loadCartItems();

    function loadCartItems() {
        $.ajax({
            url: 'fetchCart.php', // Fetch cart items from this PHP file
            method: 'GET',
            success: function (response) {
                const cartItems = JSON.parse(response);
                let cartContent = '';
                let grandTotal = 0;
                
                cartItems.forEach(item => {
                    const itemTotal = (item.price - item.discount) * item.quantity;
                    grandTotal += itemTotal;

                    cartContent += `
                        <tr>
                            <td>${item.productName}</td>
                            <td>
                                <input type="number" value="${item.quantity}" data-id="${item.cartItemID}" class="update-quantity" min="1">
                            </td>
                            <td>${item.price}</td>
                            <td>${item.discount}</td>
                            <td>${itemTotal.toFixed(2)}</td>
                            <td>
                                <button class="btn btn-danger remove-item" data-id="${item.cartItemID}">Remove</button>
                            </td>
                        </tr>`;
                });
                $('#cartItems').html(cartContent);
                $('#grandTotal').text(grandTotal.toFixed(2)); // Display grand total
            }
        });
    }

    // Update cart quantity without page reload
    $(document).on('change', '.update-quantity', function () {
        const cartItemID = $(this).data('id');
        const newQuantity = $(this).val();

        $.ajax({
            url: 'updateCart.php', // PHP script to handle quantity update
            method: 'POST',
            data: { cartItemID: cartItemID, quantity: newQuantity },
            success: function (response) {
                if (response == 'success') {
                    loadCartItems(); // Reload the cart items after update
                } else {
                    alert('Failed to update cart');
                }
            }
        });
    });

    // Remove item from cart
    $(document).on('click', '.remove-item', function () {
        const cartItemID = $(this).data('id');

        $.ajax({
            url: 'removeCartItem.php', // PHP script to handle item removal
            method: 'POST',
            data: { cartItemID: cartItemID },
            success: function (response) {
                if (response == 'success') {
                    loadCartItems(); // Reload the cart items after removal
                } else {
                    alert('Failed to remove item');
                }
            }
        });
    });

    // Checkout button click event
    $('#checkoutButton').click(function () {
        const grandTotal = parseFloat($('#grandTotal').text()); // Get the displayed grand total
        const remainingBudget = parseFloat($('#remainingBudget').val()); // Get the remaining budget

        if (grandTotal > remainingBudget) {
            // Show a custom confirmation with two options
            if (confirm(`Your total exceeds the remaining budget. Remaining budget: $${remainingBudget.toFixed(2)}. Do you want to proceed with the checkout?`)) {
                // If the user clicks "OK", proceed to checkout
                window.location.href = 'checkOutt.php?grandTotal=' + encodeURIComponent(grandTotal);
                //window.location.href = 'checkOutt.php?grandTotal=' + encodeURIComponent(grandTotal);
            } else {
                // If the user clicks "Cancel", navigate back to the cart page
                window.location.href = 'viewCart.php'; // Adjust the link if necessary
            }
        } else {
            // If within budget, proceed to checkout
            window.location.href = 'checkOutt.php?grandTotal=' + encodeURIComponent(grandTotal);
        }
    });
});
</script>
</body>
</html>

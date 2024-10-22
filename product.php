<?php
session_start();
include('conn.php');

// Fetch products
$sql = "SELECT * FROM product";
$result = $conn->query($sql);

$products = [];
if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    } else {
        echo "No products found.";
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<?php
include('header.php');
?>
<!-- Modal structure -->
<div id="loginModal" class="modal" >
    <div class="modal-content" style="width:50%; position:center;">
        <span class="close">&times;</span>
        <h2>Login or Register</h2>
        <p>You need to log in or register to add products to your cart.</p>
        <button class="btn btn-custom" onclick="window.location.href='Customer/login.html'">Login</button>
        <button class="btn btn-custom" onclick="window.location.href='Customer/register.html'">Register</button>
    </div>
</div>


<div class="container-fluid py-5">
    <div class="container py-5">
        <h1 class="mb-4">Products</h1>
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="row g-4">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                        <div class="col-md-6 col-lg-6 col-xl-4">
                            <div class="rounded position-relative product-item">
                                <div class="product-img">
                                    <img src="Seller/backend/product/<?php echo ($product['image']); ?>" class="img-fluid w-100 rounded-top" alt="<?php echo $product['productName']; ?>">
                                    </div>
                                    <div class="p-4 p-4 rounded-bottom">
                                    <h4><?php echo $product['productName']; ?></h4>
                                    <p><?php echo $product['description']; ?></p>
                                    <div class="d-flex justify-content-between flex-lg-wrap">
                                        <p class="text-dark fs-5 fw-bold mb-0">Rs <?php echo $product['price']; ?></p>
                                        <a href="#" class="btn btn-custom add-to-cart" ><i class="fa fa-shopping-cart cart"></i> Add to cart</a>
                                        <a href="productView.php?product_id=<?php echo $product['productID']; ?>">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No products available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer and other static parts of the HTML -->


<footer>
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
            <div class="container py-5">
                <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
                    <div class="row g-4">
                        <div class="col-lg-3">
                            <a href="#">
                                <h1 class="text-primary mb-0">Fruitables</h1>
                                <p class="text-secondary mb-0">Fresh products</p>
                            </a>
                        </div>
                        <div class="col-lg-6">
                            <div class="position-relative mx-auto">
                                <input class="form-control border-0 w-100 py-3 px-4 rounded-pill" type="number" placeholder="Your Email">
                                <button type="submit" class="btn btn-primary border-0 border-secondary py-3 px-4 position-absolute rounded-pill text-white" style="top: 0; right: 0;">Subscribe Now</button>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="d-flex justify-content-end pt-3">
                                <a class="btn  btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-youtube"></i></a>
                                <a class="btn btn-outline-secondary btn-md-square rounded-circle" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-light mb-3">Why People Like us!</h4>
                            <p class="mb-4">typesetting, remaining essentially unchanged. It was
                                popularised in the 1960s with the like Aldus PageMaker including of Lorem Ipsum.</p>
                            <a href="" class="btn border-secondary py-2 px-4 rounded-pill text-primary">Read More</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex flex-column text-start footer-item">
                            <a class="btn-link" href="">About Us</a>
                            <a class="btn-link" href="">Contact Us</a>
                            <a class="btn-link" href="">Privacy Policy</a>
                            <a class="btn-link" href="">Terms & Condition</a>
                            <a class="btn-link" href="">Return Policy</a>
                            <a class="btn-link" href="">FAQs & Help</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex flex-column text-start footer-item">
                            <h4 class="text-light mb-3">Account</h4>
                            <a class="btn-link" href="">My Account</a>
                            <a class="btn-link" href="">Shop details</a>
                            <a class="btn-link" href="">Shopping Cart</a>
                            <a class="btn-link" href="">Shopping List</a>
                            <a class="btn-link" href="">Order History</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-light mb-3">Contact</h4>
                            <p>Email: trollymaster@gmail.com</p>
                            <p>Phone: +0123 4567 8910</p>
                            <p>Payment Accepted</p>
                            <img src="img/payment.png" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- login script -->
        <script>
            document.getElementById('loginBtn').onclick = function () {
                document.getElementById('popup').classList.add('show');
            };

            document.getElementById('closeBtn').onclick = function () {
                document.getElementById('popup').classList.remove('show');
            };

            window.onclick = function (event) {
                if (event.target == document.getElementById('popup')) {
                    document.getElementById('popup').classList.remove('show');
                }
            };

            function selectAccountType(accountType) {
                document.getElementById('accountTypeInput').value = accountType;
                document.getElementById('accountTypeForm').submit();
            }

            // Get the modal
            var modal = document.getElementById("loginModal");

            // Get the button that opens the modal
            var addToCartButtons = document.querySelectorAll(".add-to-cart");

            // Get the <span> element that closes the modal
            var closeModal = document.querySelector(".modal .close");

            // Add event listener to open the modal when "Add to Cart" is clicked
            addToCartButtons.forEach(button => {
                button.addEventListener("click", function() {
                    modal.style.display = "block";
                });
            });

            // Add event listener to close the modal when the close button is clicked
            closeModal.addEventListener("click", function() {
                modal.style.display = "none";
            });

            // Add event listener to close the modal when clicking outside of it
            window.addEventListener("click", function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            });
        </script>

        <!-- Bootstrap JS, Popper.js, and jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
    </body>
</html>

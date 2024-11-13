<?php

// Function to get discount promotion products
function getDiscountProducts($conn) {
    $today = date("Y-m-d");
    $sql = "SELECT p.productID, p.productName, p.description, p.price, p.image, p.discounts,
                   s.name AS storeName, s.logo AS storeLogo, promo.discount, promo.promotionName
            FROM product p
            JOIN promotions promo ON p.promotionID = promo.promotionID
            JOIN store s ON p.storeID = s.regID
            WHERE promo.startDate <= '$today' AND promo.endDate >= '$today'";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


$newProducts = getNewProducts($conn);
$discountProducts = getDiscountProducts($conn);
?>
 
        <!-- Discount Promotion Products Section -->
        <h1 class="mt-5 mb-4">Discount Promotion Products</h1>
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="row g-4">
                        <?php if (!empty($discountProducts)): ?>
                            <?php foreach ($discountProducts as $product): ?>
                                <div class="col-md-6 col-lg-6 col-xl-4">
                                    <div class="rounded position-relative product-item">
                                        <div class="mt-2">
                                            <h5 style="margin-left:5px;">
                                                <?php echo htmlspecialchars($product['storeName']); ?>
                                                <?php if ($product['storeLogo']): ?>
                                                    <img src="Seller/backend/<?php echo htmlspecialchars($product['storeLogo']); ?>" alt="Store Logo" style="width: 50px; height: 50px; object-fit: cover;">
                                                <?php endif; ?>
                                            </h5>
                                        </div>
                                        <div class="product-img">
                                            <img src="Seller/backend/product/<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid w-100 rounded-top" alt="<?php echo htmlspecialchars($product['productName']); ?>">
                                        </div>
                                        <div class="p-4 rounded-bottom">
                                            <h4><?php echo htmlspecialchars($product['productName']); ?></h4>
                                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                                            <div class="d-flex justify-content-between flex-lg-wrap">
                                                <?php 
                                                $originalPrice = $product['price'];
                                                $discountedPrice = $originalPrice * (1 - $product['discount'] / 100); 
                                                ?>
                                                <!-- Display Original Price with Strikethrough if Discounted -->
                                                <?php if ($product['discount'] > 0): ?>
                                                    <p class="text-muted fs-6 mb-0" style="text-decoration: line-through;">Rs <?php echo htmlspecialchars($originalPrice); ?></p>
                                                <?php endif; ?>
                                                
                                                <!-- Display Discounted Price -->
                                                <p class="text-dark fs-5 fw-bold mb-0">
                                                    Rs <?php echo htmlspecialchars($discountedPrice); ?>
                                                </p>

                                                <!-- Add to Cart Button -->
                                                <a href="#" class="btn btn-custom add-to-cart" data-product-id="<?php echo $product['productID']; ?>">
                                                    <i class="fa fa-shopping-cart cart"></i> Add to cart
                                                </a>
                                                
                                                <!-- View Details Link -->
                                                <a href="productView.php?product_id=<?php echo $product['productID']; ?>">View Details</a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No discount promotion products available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
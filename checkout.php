<?php
session_start();

// Check if the cart exists in the session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$grandTotal = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Rennie Luxury</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">Rennie Luxury</div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="about-us.php">About Us</a></li>
                <li><a href="contact-us.php">Contact</a></li>
                <li><a href="cart.php" class="cart">Cart</a></li>
            </ul>
        </nav>
    </header>

    <section id="checkout">
        <h1>Checkout</h1>

        <div id="cart-summary">
            <h2>Order Summary</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cart)): ?>
                        <tr>
                            <td colspan="4">No items in the cart.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cart as $item): ?>
                            <?php $total = $item['price'] * $item['quantity']; ?>
                            <?php $grandTotal += $total; ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>$<?php echo number_format($total, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="total">
                <h3>Grand Total: $<?php echo number_format($grandTotal, 2); ?></h3>
            </div>
        </div>

        <form action="process_order.php" method="POST" id="checkout-form">
            <h2>Billing Details</h2>
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>

            <input required type="hidden" name="amount" value="<?php echo $grandTotal; ?>">

            <label for="address">Shipping Address:</label>
            <textarea id="address" name="address" required></textarea>

            <h2>Payment Method</h2>
            <label>
                <input type="radio" name="payment" value="credit-card" required>
                Credit/Debit Card
            </label>
            <label>
                <input type="radio" name="payment" value="paypal" required>
                PayPal
            </label>

            <button type="submit" class="btn" name="place-order">Place Order</button>
        </form>
    </section>

    <footer>
        <div class="footer-content">
            <p>&copy; 2024 Rennie Luxury. All rights reserved.</p>
            <ul class="social-links">
                <li><a href="#">Facebook</a></li>
                <li><a href="#">Twitter</a></li>
                <li><a href="#">Instagram</a></li>
            </ul>
        </div>
    </footer>
</body>
</html>

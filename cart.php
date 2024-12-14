<?php
session_start();

// Ensure cart exists in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle quantity update or item removal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $productId = $_POST['product_id'];

        if ($action === 'update' && isset($_POST['quantity'])) {
            // Update product quantity
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $productId) {
                    $item['quantity'] = max(1, intval($_POST['quantity']));
                    break;
                }
            }
        } elseif ($action === 'remove') {
            // Remove product from cart
            $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($productId) {
                return $item['id'] != $productId;
            });
        }
    }
}

// Calculate totals
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$total = $subtotal; // Add shipping if necessary
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View and manage the items in your cart at Rennie Luxury. Adjust quantities, remove items, and proceed to checkout for your wig purchase.">
    <meta name="keywords" content="wig cart, checkout, Rennie Luxury cart, wig shopping cart">
    <title>Shopping Cart - Rennie Luxury</title>
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
                <li><a href="contact.php">Contact</a></li>
                <li><a href="cart.php" class="cart">Cart</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="cart">
            <h1>Your Shopping Cart</h1>
            <p>Review the items you’ve added to your cart. You can adjust quantities, remove items, or proceed to checkout.</p>

            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($_SESSION['cart'])): ?>
                        <tr>
                            <td colspan="5">Your cart is empty. <a href="products.php">Start shopping</a>.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <tr>
                                <td>
                                    <div class="cart-item">
                                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" width="50">
                                        <p><?php echo htmlspecialchars($item['name']); ?></p>
                                    </div>
                                </td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" onchange="this.form.submit();">
                                    </form>
                                </td>
                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="btn remove-item">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="cart-summary">
                <h2>Cart Summary</h2>
                <p><strong>Subtotal:</strong> $<?php echo number_format($subtotal, 2); ?></p>
                <p><strong>Shipping:</strong> Free (Domestic Shipping)</p>
                <p><strong>Total:</strong> $<?php echo number_format($total, 2); ?></p>
            </div>

            <div class="cart-actions">
                <button class="btn continue-shopping" onclick="window.location.href='products.php'">Continue Shopping</button>
                <?php if (!empty($_SESSION['cart'])): ?>
                    <button class="btn checkout" onclick="window.location.href='checkout.php'">Proceed to Checkout</button>
                <?php endif; ?>
            </div>
        </section>
    </main>

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

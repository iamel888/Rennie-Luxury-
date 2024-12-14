<?php
session_start();

// Include database connection
include 'config.php';

// Initialize the cart in the session if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add-to-cart request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productImage = $_POST['product_image'];

    // Check if the product is already in the cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] === $productId) {
            $item['quantity'] += 1;
            $found = true;
            break;
        }
    }

    // If not found, add as a new item
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $productId,
            'name' => $productName,
            'price' => $productPrice,
            'image' => $productImage,
            'quantity' => 1
        ];
    }

    // Redirect back to the products page
    header('Location: products.php');
    exit;
}

// Fetch all products from the database
$query = "SELECT * FROM products";
$result = $conn->query($query);
if (!$result) {
    die("Error fetching products: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rennie Luxury - Products</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">Rennie Luxury</div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Wigs</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="about-us.php">About Us</a></li>
                <li><a href="contact-us.php">Contact</a></li>
                <li><a href="cart.php" class="cart">Cart (<?php echo count($_SESSION['cart']); ?>)</a></li>
            </ul>
        </nav>
    </header>

    <div id="notification" class="notification"></div>

    <main>
        <section id="product-list">
            <h2>Our Wigs</h2>
            <div class="product-grid">
                <?php while ($product = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                        <h3><?php echo $product['name']; ?></h3>
                        <p>$<?php echo number_format($product['price'], 2); ?></p>
                        <form method="POST" action="">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                            <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                            <input type="hidden" name="product_image" value="<?php echo $product['image']; ?>">
                            <button type="submit" name="add_to_cart" class="btn btn-add-to-cart">Add to Cart</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <section id="cart-actions">
            <a href="cart.php" class="btn btn-view-cart"><button>Go to Cart</button></a>
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


    <script src="cart.js"></script>
    <script>
        // Function to show notification
        function showNotification(message) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.style.display = 'block';

            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000); // Hide after 3 seconds
        }

        // Add event listeners to "Add to Cart" buttons
        document.querySelectorAll('.btn-add-to-cart').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const price = button.getAttribute('data-price');
                const image = button.getAttribute('data-image');

                // Call addToCart function from cart.js
                addToCart(id, name, price, image);

                // Show notification
                showNotification(`${name} has been added to your cart!`);
            });
        });
    </script>
</body>
</html>

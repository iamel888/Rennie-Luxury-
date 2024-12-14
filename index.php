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
    <title>Rennie Luxury</title>
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

    <section id="hero">
        <h1>Welcome to Rennie Luxury</h1>
        <p>Your ultimate destination for premium wigs and hair extensions, where luxury meets beauty.</p>
        <a href="products.php" class="btn">Shop Now</a>
        <a href="register.php" class="btn signup-btn">Sign Up</a>
    </section>

    <div id="notification" style="display: none; position: fixed; top: 20px; right: 20px; background: #333; color: #fff; padding: 10px; border-radius: 5px;">Notification</div>

    <section id="products">
        <h2>Featured Wigs</h2>
        <div class="product-grid">
            <?php
            // Connect to the database
            include 'config.php';

            // Fetch all products from the database
            $query = "SELECT * FROM products";
            $result = mysqli_query($conn, $query);
            $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

            // Shuffle and pick four random products
            shuffle($products);
            $featuredProducts = array_slice($products, 0, 4);

            // Display featured products
            foreach ($featuredProducts as $product): ?>
                <div class="product-card">
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p>$<?php echo number_format($product['price'], 2); ?></p>
                    <button class="add-to-cart" data-id="<?php echo $product['id']; ?>" data-name="<?php echo htmlspecialchars($product['name']); ?>" data-price="<?php echo $product['price']; ?>" data-image="<?php echo $product['image']; ?>">Add to Cart</button>
                </div>
            <?php endforeach; ?>
        </div>
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

    <script>
        // Initialize cart from localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Function to add a product to the cart
        function addToCart(id, name, price, image) {
            const existingProduct = cart.find(item => item.id === id);

            if (existingProduct) {
                existingProduct.quantity += 1;
            } else {
                cart.push({ id, name, price: parseFloat(price), image, quantity: 1 });
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            showNotification(`${name} has been added to your cart!`);
        }

        // Function to show notification
        function showNotification(message) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.style.display = 'block';

            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }

        // Add event listeners to "Add to Cart" buttons
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const price = button.getAttribute('data-price');
                const image = button.getAttribute('data-image');
                addToCart(id, name, price, image);
            });
        });
    </script>
</body>
</html>

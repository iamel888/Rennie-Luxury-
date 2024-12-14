<?php
// Initialize variables for form data and error messages
$email = $password = "";
$email_err = $password_err = $login_err = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty($_POST["email"])) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty($_POST["password"])) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // If no errors, check user credentials
    if (empty($email_err) && empty($password_err)) {
        // Connect to the database
        $conn = new mysqli("localhost", "root", "", "rennieLuxury");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare SQL statement to fetch user
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);

        // Execute query
        $stmt->execute();
        $stmt->store_result();

        // Check if email exists
        if ($stmt->num_rows == 1) {
            // Bind result variables
            $stmt->bind_result($id, $name, $hashed_password);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Start session and redirect to index page
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["name"] = $name;

                header("Location: index.php");
                exit();
            } else {
                $login_err = "Invalid email or password.";
            }
        } else {
            $login_err = "Invalid email or password.";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Rennie Luxury</title>
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
                <li><a href="cart.php">Cart</a></li>
            </ul>
        </nav>
    </header>

    <section id="login">
        <h2>Log In to Your Account</h2>
        <?php if (!empty($login_err)) : ?>
            <div class="error"><?php echo $login_err; ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                <span class="error"><?php echo $email_err; ?></span>
            </div>

            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <span class="error"><?php echo $password_err; ?></span>
            </div>

            <div>
                <button type="submit">Log In</button>
            </div>
            <p>Don't have an account? <a href="register.php"><button>Register</button></a></p>
        </form>
    </section>

    <footer>
        <div class="footer-content">
            <p>&copy; 2024 Rennie Luxury. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

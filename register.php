<?php
// Initialize variables for form data and error messages
$name = $email = $password = "";
$name_err = $email_err = $password_err = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty($_POST["name"])) {
        $name_err = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate email
    if (empty($_POST["email"])) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty($_POST["password"])) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // If no errors, proceed to insert into the database
    if (empty($name_err) && empty($email_err) && empty($password_err)) {
        // Connect to the database
        $conn = new mysqli("localhost", "root", "", "rennieLuxury");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Hash the password
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind the SQL statement
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password_hashed);

        // Execute the query and check if insertion was successful
        if ($stmt->execute()) {
            // Redirect to index.php on successful registration
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the connection and statement
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
    <title>Register | Rennie Luxury</title>
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

    <section id="register">
        <h2>Create an Account</h2>
        <form method="POST" action="register.php">
            <div>
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
                <span class="error"><?php echo $name_err; ?></span>
            </div>

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
                <button type="submit">Register</button>
            </div>
            <p>Already have an account? <a href="login.php"><button>Log In</button></a></p>
        </form>
    </section>


    <footer>
        <div class="footer-content">
            <p>&copy; 2024 Rennie Luxury. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

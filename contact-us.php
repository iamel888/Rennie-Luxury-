<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contact Rennie Luxury for any questions or support. We're here to help with your wig purchases, orders, and more.">
    <meta name="keywords" content="contact Rennie Luxury, support, customer service, wig inquiries, order questions">
    <title>Contact Us - Rennie Luxury</title>
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
                <li><a href="cart.php" class="cart">Cart</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="contact-us">
            <h1>Contact Us</h1>
            <p>We would love to hear from you! Whether you have a question, need support, or just want to provide feedback, feel free to reach out to us using the form below or through any of the contact methods listed.</p>

            <div class="contact-info">
                <h2>Our Contact Information</h2>
                <p><strong>Email:</strong> <a href="mailto:support@rennieluxury.com">support@rennieluxury.com</a></p>
                <p><strong>Phone:</strong> (123) 456-7890</p>
                <p><strong>Address:</strong> 123 Fashion Ave, Suite 101, City, State, 12345</p>
            </div>

            <div class="contact-form">
                <h2>Send Us a Message</h2>
                <form action="submit_form.php" method="POST">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" required>

                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="5" required></textarea>

                    <button type="submit" class="btn">Send Message</button>
                </form>
            </div>

            <div id="map">
                <h2>Our Location</h2>
                <p>Visit us at our store located at:</p>
                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3975.736688985954!2d6.976830974037424!3d4.815211540656181!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1069cef60dbc6fb9%3A0x20c3b0170d8b65be!2sIThePreacher!5e0!3m2!1sen!2sng!4v1733920788032!5m2!1sen!2sng"  
                        width="1400" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
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

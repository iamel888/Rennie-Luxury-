<?php
session_start();
// Database connection
$servername = "localhost"; // Update with your server name
$username = "root";        // Update with your database username
$password = "";            // Update with your database password
$dbname = "rennieluxury";  // Update with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate unique reference
function generateUniqueOrderReference($prefix = 'ORD', $length = 8) {
    return $prefix . '-' . time() . '-' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
}

if (isset($_POST["place-order"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $amount = $_POST["amount"];
    $address = $_POST["address"];
    $id = $_SESSION["id"];

    // Generate unique order reference
    $orderReference = generateUniqueOrderReference();

    // Insert order details into the database
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price,`address`, reference) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $id, $amount, $address, $orderReference);


    $url = "https://api.paystack.co/transaction/initialize";

    $fields = [
        'email' => $email,
        'amount' => (int) $amount*100,
    ];

    $fields_string = http_build_query($fields);

    //open connection
    $ch = curl_init();
    
    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Bearer sk_test_634b25fe3e7f34aeb0269fc75c955a984bc62ed8",
        "Cache-Control: no-cache",
    ));
    
    //So that curl_exec returns the contents of the cURL; rather than echoing it
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
    
    //execute post
    $result = curl_exec($ch);
    $auth = json_decode($result, true)["data"]["authorization_url"];

    header("location: $auth");
}


?>
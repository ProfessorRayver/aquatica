<?php
$host = 'localhost'; $db = 'all_city_aquatics'; $user = 'root'; $pass = '';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'src/Exception.php'; require 'src/PHPMailer.php'; require 'src/SMTP.php';

if (isset($_POST['submit_order'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $quantity = (int)$_POST['quantity'];
    $total_price = $_POST['total_price'];
    $payment_status = isset($_POST['payment_status']) ? $_POST['payment_status'] : 'Manual';
    
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Deduct Stock
        $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");
        $stmt->execute([$quantity, $product_id, $quantity]);

        if ($stmt->rowCount() > 0) {
            // Save Order
            $stmt_order = $pdo->prepare("INSERT INTO orders (product_name, quantity, total_price, customer_name, customer_email, customer_address) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_order->execute([$product_name, $quantity, $total_price, $name, $email, $address]);

            // Email
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'rayverdota@gmail.com';     
            $mail->Password   = 'fndb lrhw rucd kmkp';      
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('rayverdota@gmail.com', 'ACA Shop');
            $mail->addAddress('rayverdota@gmail.com');
            $mail->addReplyTo($email, $name);
            $mail->isHTML(true);
            $mail->Subject = "New Order: $product_name (x$quantity)";
            $mail->Body    = "<h3>Order Received</h3><p>Product: $product_name</p><p>Total: $$total_price</p><p>Status: <strong>$payment_status</strong></p><p>Customer: $name</p>";

            $mail->send();
            echo "<script>alert('Order Successful! ($payment_status)'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Sorry! Not enough stock.'); window.location.href='index.php';</script>";
        }
    } catch (Exception $e) { echo "Error: " . $e->getMessage(); }
} else { header("Location: index.php"); }
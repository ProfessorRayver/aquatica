<?php
$host = 'localhost'; $db = 'all_city_aquatics'; $user = 'root'; $pass = '';
use PHPMailer\PHPMailer\PHPMailer; use PHPMailer\PHPMailer\Exception;
require 'src/Exception.php'; require 'src/PHPMailer.php'; require 'src/SMTP.php';

if (isset($_POST['submit_booking'])) {
    $class_id = $_POST['class_id'];
    $class_name = $_POST['class_name'];
    $appt_date = $_POST['date'];
    $appt_time = $_POST['time'];
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("INSERT INTO bookings (class_id, class_name, client_name, client_email, client_phone, appointment_date, appointment_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$class_id, $class_name, $name, $email, $phone, $appt_date, $appt_time]);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rayverdota@gmail.com';     
        $mail->Password   = 'fndb lrhw rucd kmkp';      
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        $mail->setFrom('rayverdota@gmail.com', 'ACA Scheduler');
        $mail->addAddress('rayverdota@gmail.com');
        $mail->isHTML(true);
        $mail->Subject = "New Booking: $class_name";
        $mail->Body    = "<h3>New Reservation</h3><p>Class: $class_name</p><p>Date: $appt_date</p><p>Time: $appt_time</p><p>Client: $name</p>";

        $mail->send();
        echo "<script>alert('Booking Confirmed for $appt_date!'); window.location.href='index.php';</script>";
    } catch (Exception $e) { echo "Error: " . $e->getMessage(); }
} else { header("Location: index.php"); }
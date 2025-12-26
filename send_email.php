<?php
use PHPMailer\PHPMailer\PHPMailer; use PHPMailer\PHPMailer\Exception;
require 'src/Exception.php'; require 'src/PHPMailer.php'; require 'src/SMTP.php';

if (isset($_POST['send'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rayverdota@gmail.com';
        $mail->Password   = 'fndb lrhw rucd kmkp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        $mail->setFrom('rayverdota@gmail.com', 'ACA Contact Form');
        $mail->addAddress('rayverdota@gmail.com');
        $mail->addReplyTo($email, $name);
        $mail->isHTML(true);
        $mail->Subject = "Inquiry from $name";
        $mail->Body    = "<h3>Message from $name</h3><p>$message</p>";

        $mail->send();
        echo "<script>alert('Message Sent!'); window.location.href='index.php';</script>";
    } catch (Exception $e) { echo "Error: " . $mail->ErrorInfo; }
} else { header("Location: index.php"); }
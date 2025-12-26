<?php
$host = 'localhost'; $db = 'all_city_aquatics'; $user = 'root'; $pass = '';
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception;
require 'src/Exception.php'; 
require 'src/PHPMailer.php'; 
require 'src/SMTP.php';

if (isset($_POST['send'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    try {
        // Save to database
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message, status) VALUES (?, ?, ?, 'Unread')");
        $stmt->execute([$name, $email, $message]);

        // Send email
        $mail = new PHPMailer(true);
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
        $mail->Subject = "Contact Form: Message from $name";
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4;'>
                <div style='background: white; padding: 30px; border-radius: 10px; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #0066ff; border-bottom: 3px solid #00d4ff; padding-bottom: 10px;'>
                        New Contact Form Submission
                    </h2>
                    <div style='margin: 20px 0;'>
                        <p><strong style='color: #333;'>From:</strong> $name</p>
                        <p><strong style='color: #333;'>Email:</strong> <a href='mailto:$email'>$email</a></p>
                        <p><strong style='color: #333;'>Message:</strong></p>
                        <div style='background: #f9f9f9; padding: 15px; border-left: 4px solid #00d4ff; margin-top: 10px;'>
                            " . nl2br($message) . "
                        </div>
                    </div>
                    <hr style='border: none; border-top: 1px solid #ddd; margin: 20px 0;'>
                    <p style='color: #888; font-size: 12px;'>
                        This message was sent via the All City Aquatics contact form.
                    </p>
                </div>
            </div>
        ";

        $mail->send();
        echo "<script>
            alert('Thank you for contacting us! We\\'ll get back to you soon.');
            window.location.href='contact.php';
        </script>";
    } catch (Exception $e) { 
        echo "<script>
            alert('Message saved but email failed to send. We\\'ll still review your message.');
            window.location.href='contact.php';
        </script>";
    }
} else { 
    header("Location: contact.php"); 
}
?>
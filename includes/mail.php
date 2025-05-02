<?php
// Konfigurasi PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function kirimEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // Konfigurasi Server
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'alzierproject@gmail.com';
        $mail->Password = 'psmn hhgv rbkd vtiz'; // App Password for Gmail SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Penerima
        $mail->setFrom('alzierproject@gmail.com', 'Futsal Sayan');
        $mail->addAddress($to);

        // Konten
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $e->getMessage());
        return false;
    }
}
?>

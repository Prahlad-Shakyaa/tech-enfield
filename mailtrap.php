<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    $secretKey = "6Lc8E_IqAAAAAAD2slibCSqZWaym03-G7cPQcQqt"; // Replace with your reCAPTCHA v2 secret key
    $recaptchaResponse = $_POST["g-recaptcha-response"];

    // Verify reCAPTCHA with Google
    $verifyURL = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
        'secret' => $secretKey,
        'response' => $recaptchaResponse
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $verify = file_get_contents($verifyURL, false, $context);
    $captchaSuccess = json_decode($verify, true);

    if (!$captchaSuccess['success']) {
        die("reCAPTCHA verification failed. Please try again.");
    }


    $to = "info@techenfield.com";
    $subject = "New message from " . $name . " on landing page.";
    $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";

    $mail = new PHPMailer(true);

    try {
        // Mailtrap SMTP settings
        $mail->isSMTP();
        $mail->Host = 'live.smtp.mailtrap.io'; // Mailtrap SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'api'; // Replace with your Mailtrap username
        $mail->Password = '4f9c2a5e0990310ac1b35822fe9af884'; // Replace with your Mailtrap password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // Mailtrap port

        // Email headers
        $mail->setFrom('business@techenfield.com', 'Tech Enfield');
        $mail->addAddress($to);
        $mail->addReplyTo($email, $name);

        // Email content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $body;

        // Send email
        if ($mail->send()) {
            header("Location: https://techenfield.com");
            exit();
        } else {
            echo "Failed to send message.";
        }
    } catch (Exception $e) {
        echo "Mail Error: {$mail->ErrorInfo}";
    }
}

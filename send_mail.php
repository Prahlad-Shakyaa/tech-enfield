<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    $to = "info@techenfield.com";
    $subject = "New message from ".$name." on landing page.";
    $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";

    $headers = "From: $email" . "\r\n" .
               "Reply-To: $email" . "\r\n";

    if (mail($to, $subject, $body, $headers)) {
        header("Location: https://techenfield.com");
        exit();
    } else {
        echo "Failed to send message.";
    }
}
?>

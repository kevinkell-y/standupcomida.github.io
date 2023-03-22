<?php
// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the user's response to the reCAPTCHA challenge
    $captcha = $_POST['g-recaptcha-response'];

    // Verify the user's response with the reCAPTCHA server
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $_ENV['RECAPTCHA_SECRET_KEY'],
        'response' => $captcha,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    );

    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $result = json_decode($response);

    // Check if the reCAPTCHA challenge was successful
    if (!$result->success) {
        die('Error: reCAPTCHA challenge failed');
    }

    // Get the form fields
    $name = strip_tags(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST['message']));

    // Check if the fields are empty
    if (empty($name) || empty($email) || empty($message)) {
        die('Error: all fields are required');
    }

    // Check if the email address is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Error: invalid email address');
    }

    // Set the recipient email address
    $to = 'contact-emails@protonmail.com';

    // Set the email subject
    $subject = 'New message from your website';

    // Build the email content
    $content = "Name: $name\n";
    $content .= "Email: $email\n\n";
    $content .= "Message:\n$message\n";

    // Build the email headers
    $headers = "From: $name <$email>";

    // Send the email
    if (mail($to, $subject, $content, $headers)) {
        echo 'Success: your message has been sent';
    } else {
        echo 'Error: there was a problem sending your message';
    }

} else {
    // Display an error message if the form was not submitted
    die('Error: invalid form submission');
}
?>

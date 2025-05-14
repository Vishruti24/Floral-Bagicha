<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header('Location: index.php#contact');
        exit;
    }

    try {
        // Save to database
        $stmt = $conn->prepare("INSERT INTO contact_messages (email, subject, message) VALUES (?, ?, ?)");
        $stmt->execute([$email, $subject, $message]);

        // Send email notification to admin
        $to = SITE_EMAIL;
        $headers = "From: " . $email . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        mail($to, "Contact Form: " . $subject, $message, $headers);

        $_SESSION['success'] = "Message sent successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error sending message. Please try again later.";
        error_log($e->getMessage());
    }

    header('Location: index.php#contact');
    exit;
}

header('Location: index.php');
exit;
?>

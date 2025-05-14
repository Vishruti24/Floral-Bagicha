<?php
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all fields';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } else {
        try {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Email already registered';
            } else {
                // Create new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $hashed_password]);

                $_SESSION['user_id'] = $conn->lastInsertId();
                $_SESSION['user_name'] = $name;
                header('Location: index.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Registration failed. Please try again.';
            error_log($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="P111.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .auth-form {
            background: var(--container-color);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .auth-title {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--title-color);
        }
        .error-message {
            color: #ff3860;
            margin-bottom: 1rem;
            text-align: center;
        }
        .auth-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: var(--first-color);
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-form">
            <h2 class="auth-title">Register for <?php echo SITE_NAME; ?></h2>
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form action="register.php" method="POST">
                <div class="contact__content">
                    <input type="text" name="name" placeholder=" " class="contact__input" required>
                    <label class="contact__label">Full Name</label>
                </div>
                <div class="contact__content">
                    <input type="email" name="email" placeholder=" " class="contact__input" required>
                    <label class="contact__label">Email</label>
                </div>
                <div class="contact__content">
                    <input type="password" name="password" placeholder=" " class="contact__input" required>
                    <label class="contact__label">Password</label>
                </div>
                <div class="contact__content">
                    <input type="password" name="confirm_password" placeholder=" " class="contact__input" required>
                    <label class="contact__label">Confirm Password</label>
                </div>
                <button type="submit" class="button button--flex" style="margin: 1rem auto;">
                    Register <i class="ri-arrow-right-line button__icon"></i>
                </button>
            </form>
            <a href="login.php" class="auth-link">Already have an account? Login here</a>
            <a href="index.php" class="auth-link">Back to Home</a>
        </div>
    </div>
</body>
</html>

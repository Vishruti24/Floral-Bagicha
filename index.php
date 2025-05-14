<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Floral Bagicha - Plants Website</title>
    
    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="assets/favicon.png" type="image/x-icon">
    
    <!--=============== REMIX ICONS ===============-->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="P111.css">
</head>
<body>
    <!--==================== HEADER ====================-->
    <?php include 'includes/header.php'; ?>

    <main class="main">
        <!--==================== HOME ====================-->
        <?php include 'includes/home.php'; ?>

        <!--==================== ABOUT ====================-->
        <?php include 'includes/about.php'; ?>

        <!--==================== STEPS ====================-->
        <?php include 'includes/steps.php'; ?>

        <!--==================== PRODUCTS ====================-->
        <?php include 'includes/products.php'; ?>

        <!--==================== QUESTIONS ====================-->
        <?php include 'includes/faqs.php'; ?>

        <!--==================== CONTACT ====================-->
        <?php include 'includes/contact.php'; ?>
    </main>

    <!--==================== FOOTER ====================-->
    <?php include 'includes/footer.php'; ?>

    <!--=============== SCROLL REVEAL ===============-->
    <script src="P112.js"></script>

    <!--=============== MAIN JS ===============-->
    <script src="P111.js"></script>
</body>
</html>

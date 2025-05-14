<header class="header" id="header">
    <nav class="nav container">
        <a href="#" class="nav__logo">
            <i class="ri-leaf-line nav__logo-icon"></i> <?php echo SITE_NAME; ?>
        </a>

        <div class="nav__menu" id="nav-menu">
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="#home" class="nav__link active-link">Home</a>
                </li>
                <li class="nav__item">
                    <a href="#about" class="nav__link">About</a>
                </li>
                <li class="nav__item">
                    <a href="#products" class="nav__link">Products</a>
                </li>
                <li class="nav__item">
                    <a href="#faqs" class="nav__link">FAQs</a>
                </li>
                <li class="nav__item">
                    <a href="#contact" class="nav__link">Contact Us</a>
                </li>
                <?php if(isset($_SESSION['user_id'])): ?>
                <li class="nav__item">
                    <span class="nav__link">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                </li>
                <li class="nav__item">
                    <a href="cart.php" class="nav__link"><i class="ri-shopping-cart-line"></i> Cart</a>
                </li>
                <li class="nav__item">
                    <a href="logout.php" class="nav__link">Logout</a>
                </li>
                <?php else: ?>
                <li class="nav__item">
                    <a href="login.php" class="nav__link">Login</a>
                </li>
                <li class="nav__item">
                    <a href="register.php" class="nav__link">Register</a>
                </li>
                <?php endif; ?>
            </ul>

            <div class="nav__close" id="nav-close">
                <i class="ri-close-line"></i>
            </div>
        </div>

        <div class="nav__btns">
            <!-- Theme change button -->
            <i class="ri-moon-line change-theme" id="theme-button"></i>

            <div class="nav__toggle" id="nav-toggle">
                <i class="ri-menu-line"></i>
            </div>
        </div>
    </nav>
</header>

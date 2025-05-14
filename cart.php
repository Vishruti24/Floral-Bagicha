<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle quantity updates and removals
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $cart_id = (int)$_POST['cart_id'];
        
        if ($_POST['action'] === 'update') {
            $quantity = max(1, (int)$_POST['quantity']);
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$quantity, $cart_id, $_SESSION['user_id']]);
        } elseif ($_POST['action'] === 'remove') {
            $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
            $stmt->execute([$cart_id, $_SESSION['user_id']]);
        }
        
        header('Location: cart.php');
        exit;
    }
}

// Get cart items
$stmt = $conn->prepare("
    SELECT c.id, c.quantity, p.name, p.price, p.image_url 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll();

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="P111.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        .cart-container {
            padding: 6rem 0 2rem;
            min-height: 100vh;
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        .cart-table th,
        .cart-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        .cart-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 0.5rem;
        }
        .quantity-input {
            width: 60px;
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
        }
        .cart-total {
            text-align: right;
            margin-top: 2rem;
            font-size: 1.25rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="main">
        <section class="cart-container container">
            <h2 class="section__title-center">Shopping Cart</h2>
            
            <?php if (empty($cart_items)): ?>
                <p class="section__description" style="text-align: center;">Your cart is empty. <a href="index.php#products">Continue shopping</a></p>
            <?php else: ?>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td>
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-img">
                                <span><?php echo htmlspecialchars($item['name']); ?></span>
                            </td>
                            <td>₹<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <form action="cart.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input">
                                    <button type="submit" class="button--small button--flex">
                                        <i class="ri-refresh-line"></i>
                                    </button>
                                </form>
                            </td>
                            <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <form action="cart.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="button--small button--flex" style="background-color: #ff3860;">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="cart-total">
                    Total: ₹<?php echo number_format($total, 2); ?>
                </div>
                
                <div style="text-align: right; margin-top: 2rem;">
                    <a href="#" class="button button--flex">
                        Proceed to Checkout <i class="ri-arrow-right-line button__icon"></i>
                    </a>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="P111.js"></script>
</body>
</html>

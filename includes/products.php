<?php
// Fetch products from database
$stmt = $conn->prepare("SELECT * FROM products WHERE active = 1 ORDER BY id DESC");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="product section container" id="products">
    <h2 class="section__title-center">
        Check out our <br> products
    </h2>

    <p class="product__description">
        Here are some selected plants from our showroom, all are in excellent 
        shape and has a long life span. Buy and enjoy best quality.
    </p>

    <div class="product__container grid">
        <?php foreach($products as $product): ?>
        <article class="product__card">
            <div class="product__circle"></div>

            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product__img">

            <h3 class="product__title"><?php echo htmlspecialchars($product['name']); ?></h3>
            <span class="product__price">₹<?php echo number_format($product['price'], 2); ?></span>

            <button class="button--flex product__button" data-product-id="<?php echo $product['id']; ?>">
                <i class="ri-shopping-bag-line"></i>
            </button>
        </article>
        <?php endforeach; ?>
    </div>
</section>

<script>
document.querySelectorAll('.product__button').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.getAttribute('data-product-id');
        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Product added to cart!');
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding product to cart');
        });
    });
});
</script>

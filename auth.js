// Check authentication state
function checkAuth() {
    const user = JSON.parse(localStorage.getItem('user'));
    const authLinks = document.getElementById('auth-links');
    
    if (user) {
        // User is logged in
        authLinks.innerHTML = `
            <span class="nav__link">Welcome, ${user.name}</span>
            <a href="#" class="nav__link" onclick="handleLogout()">Logout</a>
            <a href="cart.html" class="nav__link"><i class="ri-shopping-cart-line"></i> Cart</a>
        `;
    } else {
        // User is not logged in
        authLinks.innerHTML = `
            <a href="login.html" class="nav__link">Login</a>
            <a href="register.html" class="nav__link">Register</a>
        `;
    }
}

// Handle logout
function handleLogout() {
    localStorage.removeItem('user');
    window.location.reload();
}

// Add to cart functionality
function addToCart(productId, productName, price, imageUrl) {
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) {
        window.location.href = 'login.html';
        return;
    }

    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    
    // Check if product already in cart
    const existingItem = cart.find(item => item.id === productId);
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: productId,
            name: productName,
            price: price,
            imageUrl: imageUrl,
            quantity: 1
        });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    alert('Product added to cart!');
}

// Initialize auth state when page loads
document.addEventListener('DOMContentLoaded', checkAuth);

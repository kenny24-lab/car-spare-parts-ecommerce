<?php

$wishlistCount = 0;

if (
    function_exists('isLoggedIn') &&
    isLoggedIn() &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'customer'
) {

    if (function_exists('getWishlistCount')) {

        $wishlistCount = getWishlistCount(
            $_SESSION['user_id']
        );
    }
}

?>

<header class="navbar">

<div class="container">

    <!-- Logo -->
    <a href="<?= BASE_URL ?>" class="logo">

        <img
            src="<?= BASE_URL ?>assets/images/logo/images.png"
            alt="AutoParts Hub Logo"
        >

        <span>AutoParts Hub</span>

    </a>

    <!-- Navigation -->
    <ul class="nav-links">

<li><a href="index.php">Home</a></li>

<li><a href="products.php">Products</a></li>

<li><a href="faq.php">FAQ</a></li>
<li><a href="track-order.php">🚚 Track Orders</a></li>
<li><a href="about.php">About Us</a></li>

<li><a href="contact.php">Contact</a></li>

<li class="dropdown">

<a href="#">
More ▼
</a>

<ul class="dropdown-menu">

<li>
<a href="categories.php">
📂 Categories
</a>
</li>

<li>
<a href="wishlist.php">
❤️ Wishlist
</a>
</li>

<li>
<a href="cart.php">
🛒 Cart
</a>
</li>

<li>
<a href="orders.php">
📦 Orders
</a>
</li>

</ul>

</li>

<li><a href="customer/profile.php">My Account</a></li>

<li><a href="logout.php">Logout</a></li>

</ul>

</div>

</header>
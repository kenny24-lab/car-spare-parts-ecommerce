<?php

require_once "config/init.php";

requireCustomer();

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare(
"SELECT * FROM products WHERE id=?"
);

$stmt->bind_param("i", $id);

$stmt->execute();

$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Product not found");
}

include "includes/header.php";
include "includes/navbar.php";

?>

<div class="container">

<h1>
<?= htmlspecialchars($product['name']); ?>
</h1>

<img
src="uploads/products/<?= $product['main_image']; ?>"
width="350"
>

<p>
<?= htmlspecialchars($product['description']); ?>
</p>

<p>
Brand:
<?= htmlspecialchars($product['brand']); ?>
</p>

<p>
Vehicle:
<?= htmlspecialchars($product['car_model']); ?>
</p>

<h2>
RWF <?= number_format($product['price']); ?>
</h2>

<?php if ($_SESSION['role'] === 'customer'): ?>

    <a href="cart.php?add=<?= $product['id']; ?>">
        Add To Cart
    </a>

<?php else: ?>

    <p><strong>Admin View Only</strong></p>

<?php endif; ?>


<?php if (
    isLoggedIn() &&
    $_SESSION['role'] === 'customer'
): ?>

<br><br>

<a
    href="wishlist.php?add=<?= $product['id']; ?>"
    class="wishlist-btn"
>
    ❤ Add To Wishlist
</a>

<?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>
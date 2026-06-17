<?php

require_once "../config/init.php";

requireCustomer();

?>

<!DOCTYPE html>
<html>
<head>

<title>Customer Dashboard | <?= APP_NAME ?></title>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{

    font-family:Arial,sans-serif;

    background:
    linear-gradient(
        rgba(0,0,0,0.65),
        rgba(0,0,0,0.65)
    ),
    url('../assets/images/products/pic2.jpeg');

    background-size:cover;

    background-position:center;

    background-repeat:no-repeat;

    background-attachment:fixed;

    min-height:100vh;
}

.container{
    width:1200px;
    max-width:95%;
    margin:40px auto;
}

.header-card{

    background:rgba(255,255,255,.95);

    padding:35px;

    border-radius:20px;

    backdrop-filter:blur(8px);

    box-shadow:
    0 8px 30px rgba(0,0,0,.25);

    margin-bottom:30px;
}

.header-card h1{

    color:#0056b3;

    font-size:36px;

    margin-bottom:10px;
}

.header-card p{
    color:#555;
    font-size:18px;
}

.dashboard-grid{

    display:grid;

    grid-template-columns:
    repeat(auto-fit,minmax(250px,1fr));

    gap:20px;
}


.dashboard-title{

    text-align:center;

    color:white;

    margin-bottom:30px;

    font-size:40px;

    text-shadow:
    2px 2px 10px rgba(0,0,0,.6);
}


.card{

    background:rgba(255,255,255,.92);

    backdrop-filter:blur(6px);

    padding:25px;

    border-radius:20px;

    box-shadow:
    0 8px 25px rgba(0,0,0,.25);

    transition:.3s;
}

.card:hover{

    transform:
    translateY(-8px)
    scale(1.03);

    box-shadow:
    0 15px 35px rgba(0,0,0,.35);
}

.card h3{

    margin-bottom:15px;

    color:#333;
}

.card p{

    color:#777;

    margin-bottom:20px;
}

.btn{

    display:inline-block;

    padding:12px 20px;

    border-radius:8px;

    text-decoration:none;

    color:white;

    font-weight:bold;

    transition:.3s;
}

.products{
    background:#007bff;
}

.profile{
    background:#28a745;
}

.wishlist{
    background:#ff9800;
}

.cart{
    background:#6f42c1;
}

.orders{
    background:#17a2b8;
}

.logout{
    background:#dc3545;
}

.btn:hover{
    opacity:.9;
}

.footer-box{

    margin-top:40px;

    text-align:center;
}

.footer-box a{

    text-decoration:none;

    color:#dc3545;

    font-weight:bold;
}

</style>

</head>

<body>

<div class="container">

    <h1 class="dashboard-title">

🚗 AutoParts Hub Customer Portal

</h1>    

    <!-- Welcome Card -->

    <div class="header-card">

        <h1>
            Welcome, <?= htmlspecialchars(getUserName()); ?>
        </h1>

        <p>
            Manage your profile, orders, cart,
            wishlist and browse products.
        </p>

    </div>

    <!-- Dashboard Menu -->

    <div class="dashboard-grid">

        <div class="card">

            <h3>🛒 Products</h3>

            <p>
                Browse all available spare parts.
            </p>

            <a
            href="../products.php"
            class="btn products"
            >
                Browse Products
            </a>

        </div>

        <div class="card">

    <h3>👤 My Profile</h3>

    <p>
        View and update your profile information,
        profile photo and account settings.
    </p>

    <a
        href="profile.php"
        class="btn profile"
    >
        Open Profile
    </a>

</div>
        <div class="card">

            <h3>❤️ Wishlist</h3>

            <p>
                View saved favourite products.
            </p>

            <a
            href="../wishlist.php"
            class="btn wishlist"
            >
                My Wishlist
            </a>

        </div>

        <div class="card">

            <h3>🛍 My Cart</h3>

            <p>
                Review products in your cart.
            </p>

            <a
            href="../cart.php"
            class="btn cart"
            >
                My Cart
            </a>

        </div>

        <div class="card">

            <h3>📦 Orders</h3>

            <p>
                Track your previous orders.
            </p>

            <a
            href="../orders.php"
            class="btn orders"
            >
                My Orders
            </a>

        </div>

        <div class="card">

            <h3>🚪 Logout</h3>

            <p>
                Securely sign out of your account.
            </p>

            <a
            href="../logout.php"
            class="btn logout"
            >
                Logout
            </a>

        </div>

    </div>

</div>

</body>
</html>
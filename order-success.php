<?php

require_once "config/init.php";

requireCustomer();

$orderId =
(int)$_GET['id'];

?>

<!DOCTYPE html>
<html>
<head>

<title>Order Success</title>

<style>

body{

font-family:Arial;

background:#f4f6f9;

padding:50px;

text-align:center;
}

.box{

background:white;

padding:40px;

border-radius:15px;

max-width:700px;

margin:auto;

box-shadow:
0 5px 20px rgba(0,0,0,.1);
}

.btn{

display:inline-block;

margin-top:20px;

padding:12px 20px;

background:#28a745;

color:white;

text-decoration:none;

border-radius:8px;
}

</style>

</head>

<body>

<div class="box">

<h1>
✅ Order Placed Successfully
</h1>

<p>

Your Order ID:

<strong>

#<?= $orderId ?>

</strong>

</p>

<p>

Thank you for shopping with AutoParts Hub.

</p>

<a
href="orders.php"
class="btn"
>

View Orders

</a>

</div>

</body>
</html>
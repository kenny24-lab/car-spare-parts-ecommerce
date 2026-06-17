<?php

require_once "config/init.php";

requireCustomer();

$orderId =
$_GET['order_id'] ?? 0;

?>

<!DOCTYPE html>
<html>
<head>

<title>Order Success</title>

<style>

body{
    background:#f4f6f9;
    font-family:Arial;
}

.box{
    width:600px;
    margin:100px auto;
    text-align:center;
    background:white;
    padding:50px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.1);
}

h1{
    color:#28a745;
}

a{
    display:inline-block;
    margin-top:20px;
    background:#007bff;
    color:white;
    text-decoration:none;
    padding:12px 25px;
    border-radius:8px;
}

</style>

</head>
<body>

<div class="box">

<h1>✅ Order Placed Successfully</h1>

<p>
Thank you for shopping with AutoParts Hub.
</p>

<a href="products.php">
Continue Shopping
</a>

</div>

</body>
</html>
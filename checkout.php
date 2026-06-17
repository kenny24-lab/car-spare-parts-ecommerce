<?php

require_once "config/init.php";

requireCustomer();

$cart = $_SESSION['cart'] ?? [];

if(empty($cart)){
    header("Location: cart.php");
    exit;
}

$total = 0;

foreach($cart as $item){
    $total += $item['price'] * $item['quantity'];
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Checkout</title>

<style>

body{
    font-family:Arial;
    background:#f4f6f9;
}

.container{
    width:900px;
    max-width:95%;
    margin:40px auto;
}

.checkout-box{
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,.1);
}

input,
select,
textarea{
    width:100%;
    padding:12px;
    margin-top:10px;
    margin-bottom:20px;
    border:1px solid #ddd;
    border-radius:8px;
}

.place-order{
    background:#28a745;
    color:white;
    border:none;
    padding:15px 25px;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
}

.place-order:hover{
    background:#218838;
}

.total{
    font-size:25px;
    font-weight:bold;
    color:#28a745;
    margin-bottom:20px;
}

</style>

</head>

<body>

<div class="container">

<div class="checkout-box">

<h2>Checkout</h2>

<div class="total">
Total: RWF <?= number_format($total); ?>
</div>

<form action="place-order.php" method="POST">

<label>Full Name</label>
<input type="text" name="customer_name" required>

<label>Phone Number</label>
<input type="text" name="phone" required>

<label>Delivery Address</label>
<textarea name="address" required></textarea>

<label>Payment Method</label>

<select name="payment_method" required>

<option value="">Choose Payment</option>

<option value="Mobile Money">
MTN Mobile Money
</option>

<option value="Airtel Money">
Airtel Money
</option>

<option value="Cash On Delivery">
Cash On Delivery
</option>

</select>

<button type="submit" class="place-order">
Place Order
</button>

</form>

</div>

</div>

</body>
</html>
<?php

require_once "../config/init.php";

requireAdmin();

if(!isset($_GET['id']))
{
    header("Location: orders.php");
    exit;
}

$orderId = (int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| Order Information
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
SELECT *
FROM orders
WHERE id = ?
");

$stmt->bind_param("i",$orderId);
$stmt->execute();

$order =
$stmt
->get_result()
->fetch_assoc();

if(!$order)
{
    die("Order not found");
}

/*
|--------------------------------------------------------------------------
| Order Items
|--------------------------------------------------------------------------
*/

$items = $conn->prepare("
SELECT *
FROM order_items
WHERE order_id = ?
");

$items->bind_param("i",$orderId);
$items->execute();

$orderItems =
$items
->get_result();

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>
View Order #<?= $order['id']; ?>
</title>

<style>

body{
    font-family:'Segoe UI',sans-serif;
    background:darkgray;
    margin:0;
}

.container{
    width:1200px;
    max-width:95%;
    margin:30px auto;
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.page-header h1{
    color:#1e293b;
}

.btn{
    text-decoration:none;
    padding:10px 16px;
    border-radius:8px;
    color:white;
    font-weight:600;
}

.back{
    background:#64748b;
}

.card{
    background:white;
    border-radius:15px;
    padding:25px;
    margin-bottom:25px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
}

.card h2{
    margin-top:0;
    color:#1e293b;
}

.info-grid{
    display:grid;
    grid-template-columns:
    repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}

.info-box{
    background:#f8fafc;
    padding:15px;
    border-radius:10px;
}

.label{
    font-size:13px;
    color:#64748b;
    margin-bottom:5px;
}

.value{
    font-size:16px;
    font-weight:600;
    color:#1e293b;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#2563eb;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#f8fafc;
}

.badge{
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.pending{
    background:#fff3cd;
    color:#856404;
}

.processing{
    background:#dbeafe;
    color:#1d4ed8;
}

.shipped{
    background:#ede9fe;
    color:#6d28d9;
}

.delivered{
    background:#dcfce7;
    color:#166534;
}

.cancelled{
    background:#fee2e2;
    color:#b91c1c;
}

.total-box{
    text-align:right;
    margin-top:20px;
    font-size:24px;
    font-weight:bold;
    color:#16a34a;
}

</style>

</head>

<body>

<div class="container">

<div class="page-header">

<h1>
Order #<?= $order['id']; ?>
</h1>

<a
href="orders.php"
class="btn back"
>
← Back to Orders
</a>

</div>

<!-- Order Information -->

<div class="card">

<h2>Customer Information</h2>

<div class="info-grid">

<div class="info-box">

<div class="label">
Customer Name
</div>

<div class="value">
<?= htmlspecialchars($order['customer_name']); ?>
</div>

</div>

<div class="info-box">

<div class="label">
Phone
</div>

<div class="value">
<?= htmlspecialchars($order['phone']); ?>
</div>

</div>

<div class="info-box">

<div class="label">
Payment Method
</div>

<div class="value">
<?= htmlspecialchars($order['payment_method']); ?>
</div>

</div>

<div class="info-box">

<div class="label">
Order Date
</div>

<div class="value">
<?= $order['created_at']; ?>
</div>

</div>

<div class="info-box">

<div class="label">
Order Status
</div>

<div class="value">

<span class="badge <?= $order['status']; ?>">

<?= ucfirst($order['status']); ?>

</span>

</div>

</div>

<div class="info-box">

<div class="label">
Delivery Status
</div>

<div class="value">

<span class="badge <?= $order['delivery_status']; ?>">

<?= ucfirst(
str_replace(
"_",
" ",
$order['delivery_status']
)
); ?>

</span>

</div>

</div>

</div>

<br>

<div class="label">
Delivery Address
</div>

<div class="value">
<?= nl2br(htmlspecialchars($order['address'])); ?>
</div>

</div>

<!-- Ordered Products -->

<div class="card">

<h2>Ordered Products</h2>

<table>

<tr>

<th>Product</th>
<th>Quantity</th>
<th>Price</th>
<th>Subtotal</th>

</tr>

<?php while($item = $orderItems->fetch_assoc()): ?>

<tr>

<td>

<?= htmlspecialchars($item['product_name']); ?>

</td>

<td>

<?= $item['quantity']; ?>

</td>

<td>

RWF <?= number_format($item['price']); ?>

</td>

<td>

RWF <?= number_format($item['subtotal']); ?>

</td>

</tr>

<?php endwhile; ?>

</table>

<div class="total-box">

Total:
RWF <?= number_format($order['total_amount']); ?>

</div>

</div>

</div>

</body>
</html>
<?php

require_once "config/init.php";

requireCustomer();

$userId = $_SESSION['user_id'];

$orders = $conn->query("
    SELECT *
    FROM orders
    WHERE user_id = $userId
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Track Orders</title>

<style>

body{
    font-family:'Segoe UI',sans-serif;
    background:#f5f7fb;
}

.container{
    width:1200px;
    max-width:95%;
    margin:40px auto;
}

.card{
    background:white;
    border-radius:15px;
    padding:20px;
    margin-bottom:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

h1{
    color:#1e293b;
    margin-bottom:30px;
}

.badge{
    padding:8px 14px;
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
    color:#7c3aed;
}

.in_transit{
    background:#cffafe;
    color:#155e75;
}

.delivered{
    background:#dcfce7;
    color:#166534;
}

.cancelled{
    background:#fee2e2;
    color:#991b1b;
}

.amount{
    font-size:22px;
    font-weight:bold;
    color:#2563eb;
}

</style>

</head>

<body>

<div class="container">

<h1>
🚚 Track My Orders
</h1>

<?php while($order = $orders->fetch_assoc()): ?>

<div class="card">

<h3>
Order #<?= $order['id']; ?>
</h3>

<p class="amount">
RWF <?= number_format($order['total_amount']); ?>
</p>

<p>
Placed:
<?= $order['created_at']; ?>
</p>

<p>
Payment Method:
<?= htmlspecialchars($order['payment_method']); ?>
</p>

<p>
Delivery Status:
<span class="badge <?= $order['delivery_status']; ?>">
<?= ucfirst(str_replace('_',' ',$order['delivery_status'])); ?>
</span>
</p>

</div>

<?php endwhile; ?>

</div>

</body>
</html>
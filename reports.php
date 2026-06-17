<?php

require_once "../config/init.php";

requireAdmin();

/*
|--------------------------------------------------------------------------
| Report Statistics
|--------------------------------------------------------------------------
*/

$totalRevenue = $conn->query("
    SELECT IFNULL(SUM(total_amount),0) AS total
    FROM orders
    WHERE status != 'cancelled'
")->fetch_assoc()['total'];

$totalOrders = $conn->query("
    SELECT COUNT(*) AS total
    FROM orders
")->fetch_assoc()['total'];

$totalCustomers = $conn->query("
    SELECT COUNT(*) AS total
    FROM users
    WHERE role='customer'
")->fetch_assoc()['total'];

$totalProducts = $conn->query("
    SELECT COUNT(*) AS total
    FROM products
")->fetch_assoc()['total'];

/*
|--------------------------------------------------------------------------
| Monthly Revenue
|--------------------------------------------------------------------------
*/

$monthlyRevenue = $conn->query("
    SELECT
    DATE_FORMAT(created_at,'%Y-%m') AS month,
    SUM(total_amount) AS revenue
    FROM orders
    GROUP BY month
    ORDER BY month DESC
    LIMIT 12
");

/*
|--------------------------------------------------------------------------
| Recent Payments
|--------------------------------------------------------------------------
*/

$payments = $conn->query("
    SELECT *
    FROM payments
    ORDER BY id DESC
    LIMIT 10
");

/*
|--------------------------------------------------------------------------
| Top Selling Products
|--------------------------------------------------------------------------
*/

$topProducts = $conn->query("
    SELECT
        product_name,
        SUM(quantity) AS total_sold,
        SUM(subtotal) AS revenue
    FROM order_items
    GROUP BY product_name
    ORDER BY total_sold DESC
    LIMIT 10
");

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>
Reports | <?= APP_NAME ?>
</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',sans-serif;
    background:darkgray;
    color:#1e293b;
}

.container{
    width:1300px;
    max-width:95%;
    margin:30px auto;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.header h1{
    font-size:36px;
}

.back-btn{
    background:#2563eb;
    color:white;
    text-decoration:none;
    padding:12px 18px;
    border-radius:10px;
    font-weight:600;
}

.back-btn:hover{
    background:#1d4ed8;
}

.cards{
    display:grid;
    grid-template-columns:
    repeat(auto-fit,minmax(240px,1fr));
    gap:20px;
    margin-bottom:30px;
}

.card{
    background:white;
    border-radius:16px;
    padding:25px;
    box-shadow:
    0 8px 25px rgba(0,0,0,.06);
}

.card h3{
    color:#64748b;
    margin-bottom:15px;
}

.card p{
    font-size:32px;
    font-weight:700;
}

.revenue{
    background:
    linear-gradient(
        135deg,
        #2563eb,
        #1d4ed8
    );
}

.revenue h3,
.revenue p{
    color:white;
}

.section{
    background:white;
    border-radius:16px;
    padding:25px;
    margin-bottom:25px;
    box-shadow:
    0 8px 25px rgba(0,0,0,.06);
}

.section h2{
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#2563eb;
    color:white;
    padding:14px;
    text-align:left;
}

table td{
    padding:14px;
    border-bottom:1px solid #e5e7eb;
}

table tr:hover{
    background:#f8fafc;
}

.badge{
    background:#dcfce7;
    color:#166534;
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.export-buttons{
    margin-bottom:20px;
}

.export-buttons button{
    background:#16a34a;
    color:white;
    border:none;
    padding:10px 16px;
    border-radius:8px;
    cursor:pointer;
    margin-right:10px;
}

.export-buttons button:hover{
    background:#15803d;
}

</style>

</head>

<body>

<div class="container">

<div class="header">

<h1>📊 Reports & Analytics</h1>

<a href="index.php" class="back-btn">
← Dashboard
</a>

</div>

<div class="cards">

<div class="card revenue">
<h3>Total Revenue</h3>
<p>RWF <?= number_format($totalRevenue); ?></p>
</div>

<div class="card">
<h3>Total Orders</h3>
<p><?= $totalOrders; ?></p>
</div>

<div class="card">
<h3>Customers</h3>
<p><?= $totalCustomers; ?></p>
</div>

<div class="card">
<h3>Products</h3>
<p><?= $totalProducts; ?></p>
</div>

</div>

<div class="section">

<h2>📈 Revenue By Month</h2>

<table>

<tr>
<th>Month</th>
<th>Revenue</th>
</tr>

<?php while($month = $monthlyRevenue->fetch_assoc()): ?>

<tr>

<td>
<?= $month['month']; ?>
</td>

<td>
RWF <?= number_format($month['revenue']); ?>
</td>

</tr>

<?php endwhile; ?>

</table>

</div>

<div class="section">

<h2>🏆 Top Selling Products</h2>

<table>

<tr>
<th>Product</th>
<th>Units Sold</th>
<th>Revenue</th>
</tr>

<?php while($product = $topProducts->fetch_assoc()): ?>

<tr>

<td>
<?= htmlspecialchars($product['product_name']); ?>
</td>

<td>
<?= $product['total_sold']; ?>
</td>

<td>
RWF <?= number_format($product['revenue']); ?>
</td>

</tr>

<?php endwhile; ?>

</table>

</div>

<div class="section">

<h2>💳 Recent Payments</h2>

<table>

<tr>

<th>ID</th>
<th>Order</th>
<th>Amount</th>
<th>Method</th>
<th>Status</th>
<th>Transaction</th>

</tr>

<?php while($payment = $payments->fetch_assoc()): ?>

<tr>

<td>
#<?= $payment['id']; ?>
</td>

<td>
#<?= $payment['order_id']; ?>
</td>

<td>
RWF <?= number_format($payment['amount']); ?>
</td>

<td>
<?= $payment['payment_method']; ?>
</td>

<td>
<span class="badge">
<?= ucfirst($payment['payment_status']); ?>
</span>
</td>

<td>
<?= $payment['transaction_id']; ?>
</td>

</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</body>

</html>
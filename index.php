<?php

require_once "../config/init.php";

requireAdmin();

/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/

// Products
$productCount = $conn
->query(
"SELECT COUNT(*) AS total FROM products"
)
->fetch_assoc()['total'];

// Categories
$categoryCount = $conn
->query(
"SELECT COUNT(*) AS total FROM categories"
)
->fetch_assoc()['total'];

// Customers
$customerCount = $conn
->query(
"
SELECT COUNT(*) AS total
FROM users
WHERE role='customer'
"
)
->fetch_assoc()['total'];

// Orders
$orderCount = $conn
->query(
"SELECT COUNT(*) AS total FROM orders"
)
->fetch_assoc()['total'];

// Revenue
$revenue = $conn
->query(
"
SELECT
IFNULL(
SUM(total_amount),
0
) AS revenue
FROM orders
WHERE status != 'cancelled'
"
)
->fetch_assoc()['revenue'];


/*
|--------------------------------------------------------------------------
| Inventory Value
|--------------------------------------------------------------------------
*/

$inventoryValue = $conn
->query(
"
SELECT
IFNULL(
SUM(price * stock_quantity),
0
) AS total
FROM products
"
)
->fetch_assoc()['total'];

/*
|--------------------------------------------------------------------------
| Recent Orders
|--------------------------------------------------------------------------
*/

$recentOrders =
$conn->query(
"
SELECT
orders.*,
users.full_name
FROM orders

JOIN users
ON orders.user_id = users.id

ORDER BY orders.id DESC

LIMIT 5
"
);

/*
|--------------------------------------------------------------------------
| Low Stock Products
|--------------------------------------------------------------------------
*/

$lowStock =
$conn->query(
"
SELECT *
FROM products
WHERE stock_quantity <= 5
ORDER BY stock_quantity ASC
LIMIT 5
"
);

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>
Admin Dashboard |
<?= APP_NAME ?>
</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',sans-serif;
    background:darkgrey;
    color:#333;
}

.container{
    width:1300px;
    max-width:95%;
    margin:30px auto;
}

/* Header */

.dashboard-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.dashboard-header h1{
    font-size:38px;
    color:#1e293b;
}

.welcome{
    color:#64748b;
    margin-top:8px;
}

/* Navigation */

.menu{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    margin:25px 0 35px;
}

.menu a{
    text-decoration:none;
    background:white;
    color:#2563eb;
    padding:12px 18px;
    border-radius:10px;
    font-weight:600;
    box-shadow:0 3px 10px rgba(0,0,0,.08);
    transition:.3s;
}

.menu a:hover{
    background:#2563eb;
    color:white;
    transform:translateY(-2px);
}

/* Cards */

.cards{
    display:grid;
    grid-template-columns:
    repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.card{
    background:white;
    border-radius:18px;
    padding:25px;
    box-shadow:
    0 10px 30px rgba(0,0,0,.06);
    transition:.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.card h2{
    font-size:18px;
    color:#64748b;
    margin-bottom:15px;
}

.card p{
    font-size:38px;
    font-weight:700;
    color:#1e293b;
}

/* Revenue card */

.revenue{
    background:
    linear-gradient(
        135deg,
        #2563eb,
        #1d4ed8
    );

    color:white;
}

.revenue h2,
.revenue p{
    color:white;
}

/* Sections */

.section{
    background:white;
    padding:25px;
    margin-top:30px;
    border-radius:18px;
    box-shadow:
    0 10px 30px rgba(0,0,0,.06);
}

.section h2{
    margin-bottom:20px;
    color:#1e293b;
}

/* Tables */

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#2563eb;
    color:white;
    padding:14px;
}

table td{
    padding:14px;
    border-bottom:1px solid #eee;
}

table tr:hover{
    background:#f8fafc;
}

/* Status badges */

.status-pending{
    background:#fff3cd;
    color:#856404;
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.status-processing{
    background:#dbeafe;
    color:#1d4ed8;
    padding:6px 12px;
    border-radius:20px;
}

.status-shipped{
    background:#ede9fe;
    color:#6d28d9;
    padding:6px 12px;
    border-radius:20px;
}

.status-delivered{
    background:#dcfce7;
    color:#166534;
    padding:6px 12px;
    border-radius:20px;
}

.warning{
    color:#dc2626;
    font-weight:700;
}

.inventory-card{
    background:
    linear-gradient(
        135deg,
        #16a34a,
        #15803d
    );

    color:white;
}

.inventory-card h2,
.inventory-card p{
    color:white;
}


</style>

</head>

<body>

<div class="container">

<div class="dashboard-header">

<div>

<h1>Admin Dashboard</h1>

<p class="welcome">
Welcome back,
<strong><?= getUserName(); ?></strong>
</p>

</div>

</div>

<div class="menu">

<a href="products.php">📦 Products</a>

<a href="categories.php">🗂 Categories</a>

<a href="inventory.php">📦 Inventory</a>

<a href="reports.php">📈 Reports</a>

<a href="orders.php">🛒 Orders</a>

<a href="users.php">👥 Users</a>

<a href="payments.php">💳 Payments</a>

<a href="delivery.php">🚚 Delivery</a>

<a href="../logout.php">🚪 Logout</a>

</div>

<!-- Statistics -->

<div class="cards">

<div class="card">

<h2>Products</h2>

<p>

<?= $productCount; ?>

</p>

</div>

<div class="card">

<h2>Categories</h2>

<p>

<?= $categoryCount; ?>

</p>

</div>

<div class="card">

<h2>Customers</h2>

<p>

<?= $customerCount; ?>

</p>

</div>

<div class="card">

<h2>Orders</h2>

<p>

<?= $orderCount; ?>

</p>

</div>


<div class="card inventory-card">

<h2>Inventory Value</h2>

<p>

RWF <?= number_format(
$inventoryValue
); ?>

</p>

</div>



<div class="card revenue">

<h2>Total Revenue</h2>

<p>
RWF <?= number_format($revenue); ?>
</p>

</div>

</div>

<!-- Recent Orders -->

<div class="section">

<h2>

Recent Orders

</h2>

<table>

<tr>

<th>ID</th>

<th>Customer</th>

<th>Total</th>

<th>Status</th>

<th>Date</th>

</tr>

<?php while(
$order =
$recentOrders->fetch_assoc()
): ?>

<tr>

<td>

#<?= $order['id']; ?>

</td>

<td>

<?= htmlspecialchars(
$order['full_name']
); ?>

</td>

<td>

RWF <?= number_format(
$order['total_amount']
); ?>

</td>

<td>

<span class="status-<?= $order['status']; ?>">

<?= ucfirst($order['status']); ?>

</span>

</td>

<td>

<?= $order['created_at']; ?>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>

<!-- Low Stock -->

<div class="section">

<h2>

Low Stock Alert

</h2>

<table>

<tr>

<th>ID</th>

<th>Product</th>

<th>Stock</th>

</tr>

<?php while(
$product =
$lowStock->fetch_assoc()
): ?>

<tr>

<td>

<?= $product['id']; ?>

</td>

<td>

<?= htmlspecialchars(
$product['name']
); ?>

</td>

<td class="warning">

<?= $product['stock_quantity']; ?>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</body>

</html>
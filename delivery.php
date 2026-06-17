<?php

require_once "../config/init.php";

requireAdmin();

$orders = $conn->query("
SELECT *
FROM orders
ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Delivery Tracking</title>

<style>

body{
font-family:'Segoe UI';
background:#f4f6f9;
margin:0;
}

.container{
width:1300px;
max-width:95%;
margin:30px auto;
}

.card{
background:white;
padding:25px;
border-radius:15px;
box-shadow:0 5px 20px rgba(0,0,0,.08);
}

table{
width:100%;
border-collapse:collapse;
}

th{
background:#2563eb;
color:white;
padding:14px;
}

td{
padding:14px;
border-bottom:1px solid #eee;
}

.status{
padding:6px 12px;
border-radius:20px;
font-size:13px;
font-weight:600;
}

.pending{
background:#fff3cd;
color:#856404;
}

.confirmed{
background:#dbeafe;
color:#1e40af;
}

.processing{
background:#ede9fe;
color:#6d28d9;
}

.shipped{
background:#cffafe;
color:#155e75;
}

.out_for_delivery{
background:#ffedd5;
color:#c2410c;
}

.delivered{
background:#dcfce7;
color:#166534;
}

.btn{
background:#2563eb;
color:white;
padding:8px 12px;
text-decoration:none;
border-radius:8px;
}

</style>

</head>

<body>

<div class="container">

<div class="card">

<h2>🚚 Delivery Tracking</h2>

<br>

<table>

<tr>
<th>Order</th>
<th>Customer</th>
<th>Tracking</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row=$orders->fetch_assoc()): ?>

<tr>

<td>#<?= $row['id'] ?></td>

<td>
<?= htmlspecialchars(
$row['customer_name']
) ?>
</td>

<td>
<?= $row['tracking_number'] ?>
</td>

<td>

<span class="status <?= $row['delivery_status'] ?>">

<?= ucfirst(
str_replace(
"_",
" ",
$row['delivery_status']
)
) ?>

</span>

</td>

<td>

<a
class="btn"
href="update-delivery.php?id=<?= $row['id'] ?>"
>
Update
</a>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</body>
</html>
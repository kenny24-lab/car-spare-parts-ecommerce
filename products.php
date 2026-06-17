<?php

require_once "../config/init.php";

requireAdmin();

$search = $_GET['search'] ?? '';

$sql = "
SELECT
products.*,
categories.name AS category_name
FROM products

LEFT JOIN categories
ON products.category_id = categories.id
";

if(!empty($search))
{
    $search = $conn->real_escape_string($search);

    $sql .= "
    WHERE products.name LIKE '%$search%'
    ";
}

$sql .= " ORDER BY products.id DESC";

$products = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Manage Products</title>

<style>

body{
    font-family:'Segoe UI',sans-serif;
    background:#f1f5f9;
    margin:0;
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
    margin-bottom:20px;
}

.header h1{
    color:#1e293b;
}

.btn{
    background:#2563eb;
    color:white;
    text-decoration:none;
    padding:12px 18px;
    border-radius:8px;
    font-weight:600;
}

.card{
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
}

.search-box{
    margin-bottom:20px;
}

.search-box input{
    width:300px;
    padding:10px;
    border:1px solid #ddd;
    border-radius:8px;
}

.search-box button{
    padding:10px 15px;
    border:none;
    background:#2563eb;
    color:white;
    border-radius:8px;
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

.product-image{
    width:80px;
    height:80px;
    object-fit:cover;
    border-radius:10px;
    border:1px solid #ddd;
    box-shadow:0 2px 8px rgba(0,0,0,.08);
}

.stock-normal{
    color:#16a34a;
    font-weight:700;
}

.stock-low{
    color:#dc2626;
    font-weight:700;
}

.badge{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.available{
    background:#dcfce7;
    color:#166534;
}

.out{
    background:#fee2e2;
    color:#b91c1c;
}

.action{
    display:inline-block;
    padding:8px 14px;
    border-radius:8px;
    text-decoration:none;
    color:white;
    font-size:13px;
    font-weight:600;
    transition:.3s;
}

.actions{
    display:flex;
    gap:8px;
    justify-content:center;
}

.action:hover{
    transform:translateY(-2px);
}

.edit{
    background:#10b981;
}

.delete{
    background:#dc2626;
}

.stock{
    background:#2563eb;
}

.back{
    background:#64748b;
}

</style>

</head>

<body>

<div class="container">

<div class="header">

<h1>Product Management</h1>

<div>

<a href="index.php" class="btn back">
Dashboard
</a>

<a href="add-product.php" class="btn">
+ Add Product
</a>

</div>

</div>

<div class="card">

<form class="search-box">

<input
type="text"
name="search"
placeholder="Search product..."
value="<?= htmlspecialchars($search); ?>"
>

<button type="submit">
Search
</button>

</form>

<table>

<tr>

<th>Image</th>
<th>Product</th>
<th>Category</th>
<th>Price</th>
<th>Stock</th>
<th>Status</th>
<th>Actions</th>

</tr>

<?php while($product = $products->fetch_assoc()): ?>

<tr>

<td>

<img
src="../uploads/products<?= $product['main_image']; ?>"
class="product-image"
alt="<?= htmlspecialchars($product['name']); ?>"
>

</td>

<td>

<?= htmlspecialchars($product['name']); ?>

</td>

<td>

<?= htmlspecialchars($product['category_name']); ?>

</td>

<td>

RWF <?= number_format($product['price']); ?>

</td>

<td>

<?php if($product['stock_quantity'] <= 5): ?>

<span class="stock-low">

<?= $product['stock_quantity']; ?>

</span>

<?php else: ?>

<span class="stock-normal">

<?= $product['stock_quantity']; ?>

</span>

<?php endif; ?>

</td>

<td>

<?php if($product['status']=='available'): ?>

<span class="badge available">
Available
</span>

<?php else: ?>

<span class="badge out">
Out Of Stock
</span>

<?php endif; ?>

</td>

<td>

<div class="actions">

<a
href="edit-product.php?id=<?= $product['id']; ?>"
class="action edit">
✏ Edit
</a>

<a
href="inventory.php?product_id=<?= $product['id']; ?>"
class="action stock">
📦 Stock
</a>

<a
href="delete-product.php?id=<?= $product['id']; ?>"
class="action delete"
onclick="return confirm('Delete this product?')">
🗑 Delete
</a>

</div>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</body>
</html>
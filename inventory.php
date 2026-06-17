<?php

require_once "../config/init.php";

requireAdmin();

/*
|--------------------------------------------------------------------------
| Add Stock
|--------------------------------------------------------------------------
*/

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $productId = (int)$_POST['product_id'];
    $quantity  = (int)$_POST['quantity'];
    $notes     = trim($_POST['notes']);

    if($quantity > 0){

        $productQuery = $conn->prepare("
            SELECT stock_quantity
            FROM products
            WHERE id=?
        ");

        $productQuery->bind_param(
            "i",
            $productId
        );

        $productQuery->execute();

        $product =
        $productQuery
        ->get_result()
        ->fetch_assoc();

        if($product){

            $previousStock =
            $product['stock_quantity'];

            $newStock =
            $previousStock + $quantity;

            $status =
            ($newStock > 0)
            ?
            'available'
            :
            'out_of_stock';

            /*
            |--------------------------------------------------------------------------
            | Update Product Stock
            |--------------------------------------------------------------------------
            */

            $update = $conn->prepare("
                UPDATE products
                SET
                stock_quantity=?,
                status=?
                WHERE id=?
            ");

            $update->bind_param(
                "isi",
                $newStock,
                $status,
                $productId
            );

            $update->execute();

            /*
            |--------------------------------------------------------------------------
            | Save Inventory Log
            |--------------------------------------------------------------------------
            */

            $inventory = $conn->prepare("
                INSERT INTO inventory_transactions
                (
                    product_id,
                    transaction_type,
                    quantity,
                    previous_stock,
                    new_stock,
                    notes
                )
                VALUES
                (
                    ?,
                    'stock_in',
                    ?,
                    ?,
                    ?,
                    ?
                )
            ");

            $inventory->bind_param(
                "iiiis",
                $productId,
                $quantity,
                $previousStock,
                $newStock,
                $notes
            );

            $inventory->execute();

            $_SESSION['success'] =
            "Stock updated successfully.";
        }
    }

    header("Location: inventory.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Products
|--------------------------------------------------------------------------
*/

$products = $conn->query("
    SELECT *
    FROM products
    ORDER BY name ASC
");

?>
<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>
Inventory Management
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

.card{
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
    margin-bottom:25px;
}

h1{
    color:#1e293b;
}

.success{
    background:#dcfce7;
    color:#166534;
    padding:12px;
    border-radius:8px;
    margin-bottom:20px;
}

form{
    display:grid;
    grid-template-columns:
    2fr
    1fr
    2fr
    auto;
    gap:10px;
}

input,
select,
textarea{
    padding:12px;
    border:1px solid #ddd;
    border-radius:8px;
}

button{
    background:#2563eb;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    cursor:pointer;
}

button:hover{
    background:#1d4ed8;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#2563eb;
    color:white;
    padding:12px;
}

table td{
    padding:12px;
    border-bottom:1px solid #eee;
}

.badge-green{
    background:#dcfce7;
    color:#166534;
    padding:5px 10px;
    border-radius:20px;
}

.badge-red{
    background:#fee2e2;
    color:#991b1b;
    padding:5px 10px;
    border-radius:20px;
}

</style>

</head>

<body>

<div class="container">

<h1>📦 Inventory Management</h1>

<?php if(isset($_SESSION['success'])): ?>

<div class="success">

<?= $_SESSION['success']; ?>

</div>

<?php unset($_SESSION['success']); ?>

<?php endif; ?>

<div class="card">

<h2>Add Stock</h2>

<form method="POST">

<select
name="product_id"
required
>

<option value="">
Select Product
</option>

<?php

$productList =
$conn->query("
SELECT id,name
FROM products
ORDER BY name
");

while(
$item =
$productList->fetch_assoc()
):

?>

<option value="<?= $item['id']; ?>">

<?= htmlspecialchars(
$item['name']
); ?>

</option>

<?php endwhile; ?>

</select>

<input
type="number"
name="quantity"
placeholder="Quantity"
required
>

<input
type="text"
name="notes"
placeholder="Notes"
>

<button type="submit">

Add Stock

</button>

</form>

</div>

<div class="card">

<h2>Current Inventory</h2>

<table>

<tr>

<th>ID</th>

<th>Product</th>

<th>Price</th>

<th>Stock</th>

<th>Status</th>

</tr>

<?php while(
$product =
$products->fetch_assoc()
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

<td>

RWF
<?= number_format(
$product['price']
); ?>

</td>

<td>

<?= $product['stock_quantity']; ?>

</td>

<td>

<?php if(
$product['status']
==
'available'
): ?>

<span class="badge-green">

Available

</span>

<?php else: ?>

<span class="badge-red">

Out of Stock

</span>

<?php endif; ?>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</body>
</html>
<?php

require_once "../config/init.php";

requireAdmin();

if(!isset($_GET['id'])){
    header("Location: orders.php");
    exit;
}

$orderId = (int)$_GET['id'];

$order = $conn->query("
    SELECT *
    FROM orders
    WHERE id = $orderId
")->fetch_assoc();

if(!$order){
    die("Order not found");
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $status = $_POST['delivery_status'];

    $stmt = $conn->prepare("
        UPDATE orders
        SET delivery_status = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "si",
        $status,
        $orderId
    );

    $stmt->execute();

    $_SESSION['success'] =
    "Delivery status updated successfully.";

    header("Location: orders.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Update Delivery</title>

<style>

body{
    font-family:'Segoe UI',sans-serif;
    background:#f5f7fb;
}

.container{
    width:600px;
    margin:50px auto;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

h2{
    margin-bottom:20px;
    color:#1e293b;
}

select{
    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:8px;
    margin-bottom:20px;
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

.back-btn{
    text-decoration:none;
    margin-left:10px;
}

</style>

</head>

<body>

<div class="container">

<h2>
Update Delivery Status
</h2>

<p>
Order #<?= $order['id']; ?>
</p>

<form method="POST">

<select
name="delivery_status"
required
>

<option value="pending"
<?= $order['delivery_status']=='pending'?'selected':'' ?>>
Pending
</option>

<option value="processing"
<?= $order['delivery_status']=='processing'?'selected':'' ?>>
Processing
</option>

<option value="shipped"
<?= $order['delivery_status']=='shipped'?'selected':'' ?>>
Shipped
</option>

<option value="in_transit"
<?= $order['delivery_status']=='in_transit'?'selected':'' ?>>
In Transit
</option>

<option value="delivered"
<?= $order['delivery_status']=='delivered'?'selected':'' ?>>
Delivered
</option>

<option value="cancelled"
<?= $order['delivery_status']=='cancelled'?'selected':'' ?>>
Cancelled
</option>

</select>

<button type="submit">
Update Status
</button>

<a
href="orders.php"
class="back-btn"
>
Back
</a>

</form>

</div>

</body>
</html>
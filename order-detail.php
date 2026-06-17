<?php

require_once "config/init.php";

requireCustomer();

$orderId = isset($_GET['id'])
    ? (int)$_GET['id']
    : 0;

$userId = $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| Get Order
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "
    SELECT *
    FROM orders
    WHERE id = ?
    AND user_id = ?
    LIMIT 1
    "
);

$stmt->bind_param(
    "ii",
    $orderId,
    $userId
);

$stmt->execute();

$order = $stmt
    ->get_result()
    ->fetch_assoc();

if (!$order) {

    die("Order not found.");
}

/*
|--------------------------------------------------------------------------
| Get Payment Information
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "
    SELECT *
    FROM payments
    WHERE order_id = ?
    LIMIT 1
    "
);

$stmt->bind_param(
    "i",
    $orderId
);

$stmt->execute();

$payment = $stmt
    ->get_result()
    ->fetch_assoc();

/*
|--------------------------------------------------------------------------
| Get Order Items
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "
    SELECT
    oi.*,
    p.main_image,
    p.brand
FROM order_items oi
LEFT JOIN products p
ON oi.product_id = p.id
WHERE oi.order_id = ?
    "
);

$stmt->bind_param(
    "i",
    $orderId
);

$stmt->execute();

$items = $stmt->get_result();

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>
Order #<?= $order['id']; ?> |
<?= APP_NAME ?>
</title>

<style>

body{
    margin:0;
    padding:0;
    font-family:'Segoe UI',Tahoma,sans-serif;

    background:
    linear-gradient(
    135deg,
    #3e4247,
    #e4ecf7
    );

    min-height:100vh;
}

.container{
    width:1100px;
    max-width:95%;
    margin:30px auto;
}

.card{
    background:white;
    padding:30px;
    border-radius:15px;
    margin-bottom:25px;

    box-shadow:
    0 10px 25px rgba(0,0,0,.08);

    transition:.3s;
}

.card:hover{
    transform:translateY(-3px);
}

.item{
    display:flex;
    gap:20px;
    margin-bottom:20px;
    padding-bottom:20px;
    border-bottom:1px solid #eee;
}

.item img{
    width:120px;
    height:120px;
    object-fit:cover;
    border-radius:8px;
}

.pending,
.processing,
.shipped,
.delivered,
.cancelled{

display:inline-block;
padding:8px 15px;
border-radius:20px;
font-weight:bold;
color:white;
}

.pending{
background:#ff9800;
}

.processing{
background:#2196f3;
}

.shipped{
background:#673ab7;
}

.delivered{
background:#28a745;
}

.cancelled{
background:#dc3545;
}
.paid{
    color:green;
    font-weight:bold;
}

.failed{
    color:red;
    font-weight:bold;
}

.btn{
    display:inline-block;
    padding:10px 20px;
    background:#007bff;
    color:white;
    text-decoration:none;
    border-radius:5px;
}

.summary{

background:#f8fbff;

padding:25px;

border-radius:12px;

border:1px solid #dbe8ff;
}


.page-header{

background:
linear-gradient(
135deg,
#007bff,
#00c6ff
);

padding:35px;

border-radius:15px;

color:white;

margin-bottom:25px;

box-shadow:
0 10px 25px rgba(0,0,0,.1);
}

.page-header h1{
margin:0;
}

.page-header p{
margin-top:10px;
opacity:.9;
}

</style>

</head>

<body>

<div class="container">

<div class="page-header">

    <h1>
        📦 Order #<?= $order['id']; ?>
    </h1>

    <p>
        Track your order, payment and purchased products
    </p>

</div>

<a
href="#"
onclick="window.print()"
class="btn"
style="background:#28a745;"
>
🖨 Print Invoice
</a>


<a
href="orders.php"
class="btn"
>
← Back To Orders
</a>

<br><br>

<!-- Order Information -->

<div class="card">

<h2>
Order Information
</h2>

<p>

<strong>Order ID:</strong>

#<?= $order['id']; ?>

</p>

<p>

<strong>Status:</strong>

<span class="<?= $order['status']; ?>">

<?= ucfirst($order['status']); ?>

</span>

</p>

<p>

<strong>Total Amount:</strong>

RWF <?= number_format(
    $order['total_amount']
); ?>

</p>

<p>

<strong>Order Date:</strong>

<?= $order['created_at']; ?>

</p>

</div>


<div class="card">

<h2>Customer Information</h2>

<p>
<strong>Name:</strong>
<?= htmlspecialchars($order['customer_name']); ?>
</p>

<p>
<strong>Phone:</strong>
<?= htmlspecialchars($order['phone']); ?>
</p>

<p>
<strong>Address:</strong>
<?= nl2br(htmlspecialchars($order['address'])); ?>
</p>

</div>


<!-- Order Progress -->
<div class="card">

<h2>Order Progress</h2>

<ul>

<li>✅ Order Received</li>

<li>
<?= $order['status'] != 'pending'
? '✅'
: '⏳'; ?>
Processing
</li>

<li>
<?= in_array(
$order['status'],
['shipped','delivered']
)
? '✅'
: '⏳'; ?>
Shipped
</li>

<li>
<?= $order['status'] == 'delivered'
? '✅'
: '⏳'; ?>
Delivered
</li>

</ul>

</div>

<div class="card">

<h2>🚚 Delivery Information</h2>

<p>

Estimated Delivery:

<strong>

1 - 3 Business Days

</strong>

</p>

<p>

Delivery Status:

<strong>

<?= ucfirst($order['status']); ?>

</strong>

</p>

</div>

<!-- Payment Information -->

<div class="card">

<h2>
Payment Information
</h2>

<?php if($payment): ?>

<p>

<strong>Payment Method:</strong>

<?= ucwords(
    str_replace(
        "_",
        " ",
        $payment['payment_method']
    )
); ?>

</p>

<p>

<strong>Payment Status:</strong>

<span class="<?= $payment['payment_status']; ?>">

<?= ucfirst(
    $payment['payment_status']
); ?>

</span>

</p>

<p>

<strong>Transaction ID:</strong>

<?= $payment['transaction_id']
    ? $payment['transaction_id']
    : 'N/A'; ?>

</p>

<p>

<strong>Paid At:</strong>

<?= $payment['paid_at']
    ? $payment['paid_at']
    : 'Not Paid Yet'; ?>

</p>

<?php else: ?>

<p>
No payment record found.
</p>

<?php endif; ?>

</div>

<!-- Order Items -->

<div class="card">

<h2>
Ordered Products
</h2>

<?php

$grandTotal = 0;

while(
$item = $items->fetch_assoc()
):

$subtotal =
$item['price']
*
$item['quantity'];

$grandTotal += $subtotal;

?>

<div class="item">

<img
src="uploads/products/<?= htmlspecialchars($item['main_image']); ?>"
alt="<?= htmlspecialchars($item['product_name']); ?>"
onerror="this.src='assets/images/no-image.png';"
>


<div>

<h3>

<?= htmlspecialchars($item['product_name']); ?>

</h3>

<p>

Brand:

<?= htmlspecialchars(
$item['brand']
); ?>

</p>

<p>

Price:

RWF <?= number_format(
$item['price']
); ?>

</p>

<p>

Quantity:

<?= $item['quantity']; ?>

</p>

<p>

Subtotal:

RWF <?= number_format(
$subtotal
); ?>

</p>

</div>

</div>

<?php endwhile; ?>

</div>

<!-- Order Summary -->

<div class="card">

<h2>
Order Summary
</h2>

<div class="summary">

<?php

$vat = round(
$grandTotal * 0.18
);

$delivery =
2000;

$finalTotal =
$grandTotal + $vat + $delivery;

?>

<p>
Subtotal:
<strong>
RWF <?= number_format($grandTotal); ?>
</strong>
</p>

<p>
VAT (18%):
<strong>
RWF <?= number_format($vat); ?>
</strong>
</p>

<p>
Delivery:
<strong>
RWF <?= number_format($delivery); ?>
</strong>
</p>

<hr>

<h2>

Grand Total:

RWF <?= number_format($finalTotal); ?>

</h2>


<a
href="reorder.php?id=<?= $order['id']; ?>"
class="btn"
style="background:#ff6600;"
>
🔄 Order Again
</a>

</div>

</div>

</div>

</body>

</html>
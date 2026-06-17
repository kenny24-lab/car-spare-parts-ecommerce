<?php

require_once "../config/init.php";

requireAdmin();

$sql = "

SELECT
payments.*,
orders.user_id,
users.full_name

FROM payments

JOIN orders
ON payments.order_id = orders.id

JOIN users
ON orders.user_id = users.id

ORDER BY payments.id DESC

";

$payments =
$conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Payments</title>
</head>
<body>

<h1>Payments</h1>

<table border="1" cellpadding="10">

<tr>

<th>ID</th>

<th>Customer</th>

<th>Order</th>

<th>Amount</th>

<th>Method</th>

<th>Status</th>

<th>Action</th>

</tr>

<?php while(
$payment =
$payments->fetch_assoc()
): ?>

<tr>

<td>
<?= $payment['id']; ?>
</td>

<td>
<?= $payment['full_name']; ?>
</td>

<td>
#<?= $payment['order_id']; ?>
</td>

<td>
RWF <?= number_format(
$payment['amount']
); ?>
</td>

<td>
<?= $payment['payment_method']; ?>
</td>

<td>
<?= $payment['payment_status']; ?>
</td>

<td>

<a href="approve-payment.php?id=<?= $payment['id']; ?>">
Approve
</a>

|

<a href="reject-payment.php?id=<?= $payment['id']; ?>">
Reject
</a>

</td>

</tr>

<?php endwhile; ?>

</table>

</body>
</html>
<?php

require_once "../config/init.php";

requireAdmin();

$id = (int)$_GET['id'];

$stmt = $conn->prepare(
"
SELECT *
FROM payments
WHERE id = ?
"
);

$stmt->bind_param(
"i",
$id
);

$stmt->execute();

$payment =
$stmt->get_result()->fetch_assoc();

if(!$payment){

die("Payment not found");
}

$conn->begin_transaction();

try{

$stmt = $conn->prepare(
"
UPDATE payments
SET
payment_status='paid',
paid_at=NOW()
WHERE id=?
"
);

$stmt->bind_param(
"i",
$id
);

$stmt->execute();

$stmt = $conn->prepare(
"
UPDATE orders
SET status='processing'
WHERE id=?
"
);

$stmt->bind_param(
"i",
$payment['order_id']
);

$stmt->execute();

$conn->commit();

header(
"Location: payments.php"
);

exit;

}
catch(Exception $e){

$conn->rollback();

die($e->getMessage());

}